<?php

namespace App\Services;

use App\Exceptions\WrongHeaderException;
use App\Helpers\CSVHelper;
use App\Http\Requests\User\ImportRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{DB, File, Log, Session, Storage, Validator};
use Illuminate\Validation\ValidationException;
use Throwable;
use ValueError;

class AdminService
{
    public function __construct(protected UserRepository $userRepository) {
    }

    public function import(ImportRequest $request) {
        $file = $request->file('import_file');

        if (! $file instanceof UploadedFile) {
            return;
        }

        // temp save uploaded file
        $filePath = $file->storeAs('tmp', Session::getId() . '.csv');
        $fullFilePath = Storage::path($filePath);

        // get info for this kind of import
        $importType = $request->getImportType();
        $importValidationRules = CSVHelper::getImportValidationRules($importType);
        $importMappings = CSVHelper::getImportMappings($importType);
        $importHeader = array_keys($importValidationRules);

        // create a validator to use during import
        $validator = Validator::make([], $importValidationRules);
        $validator->stopOnFirstFailure(false);

        // to store all validation errors during the import
        $dataValidationErrors = [];

        $finalException = null;

        DB::beginTransaction();
        try {
            CSVHelper::readCSV(
                $fullFilePath,
                $importHeader,
                function (
                    int $rowNumber,
                    array $rowData,
                ) use (
                    $importHeader,
                    $importMappings,
                    $validator,
                    &$dataValidationErrors
                ) {
                    // parsed empty line, skip
                    if (count($rowData) == 1 && $rowData[0] === null) {
                        return;
                    }

                    // data row start at 0, spec says 2
                    $realRowNumber = 2 + $rowNumber;

                    // combine with header to make [header1 => value1, header2 => ...]
                    try {
                        $rowData = array_combine($importHeader, $rowData);
                    } catch (ValueError $ve) {
                        // throw again to force quit early since this doesn't count as data error
                        throw new WrongHeaderException('Data columns dont match with headers');
                    }

                    // validate
                    try {
                        $validator->setData($rowData);
                        $validator->validate();
                    } catch (ValidationException $ve) {
                        // if fail, don't throw but save all errors to throw altogether
                        foreach ($ve->errors() as $errorsPerField) {
                            foreach ($errorsPerField as $individualError) {
                                $dataValidationErrors[] = "Row {$realRowNumber}: " . $individualError;
                            }
                        }
                    }

                    // swap header with real column name
                    $importData = [];
                    foreach ($rowData as $headerColumn => $value) {
                        $importData[$importMappings[$headerColumn]] = $value;
                    }

                    // real import
                    $id = $importData['id'];
                    $email = $importData['email'];

                    // insert case
                    if ($id === '') {
                        // check for duplicate email
                        if ($this->userRepository->has($email, 'email')) {
                            $dataValidationErrors[] = "Row {$realRowNumber}: " . getMessage('E009', ['Email']);
                        } else {
                            // add user, unset empty id in import data
                            unset($importData['id']);
                            $this->userRepository->save(null, $importData);
                        }
                    } else {
                        // non-existent id case
                        if (! $this->userRepository->has($id)) {
                            $dataValidationErrors[] = "Row {$realRowNumber}: " . getMessage('E015', ['User']);
                        } else {
                            // edit case
                            // check if others have duplicate email
                            if ($this->userRepository->hasOther($email, 'email', $id)) {
                                $dataValidationErrors[] = "Row {$realRowNumber}: " . getMessage('E009', ['Email']);
                            } else {
                                // edit user
                                $this->userRepository->save($id, $importData);
                            }
                        }
                    }
                },
            );
            if (count($dataValidationErrors) > 0) {
                throw ValidationException::withMessages($dataValidationErrors);
            }
        // catch wrong header cases: incorrect header or failing to combine header with row
        } catch (WrongHeaderException $whe) {
            DB::rollBack();
            Log::error('Wrong header: ' . $whe->getMessage());
            $finalException = ValidationException::withMessages([getMessage('E008')]);
        // catch row validation errors
        } catch (ValidationException $t) {
            DB::rollBack();
            Log::error($t->getMessage());
            $finalException = $t;
        // catch everything else
        } catch (Throwable $t) {
            DB::rollBack();
            Log::error($t->getMessage());
            $finalException = ValidationException::withMessages([getMessage('E014')]);;
        } finally {
            // delete tmp file
            Log::info(File::delete($fullFilePath));
            if ($finalException !== null) {
                DB::rollBack();
                throw $finalException;
            }
            else {
                DB::commit();
            }
        }
    }
}
