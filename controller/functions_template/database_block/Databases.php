<?php

require_once __DIR__ . '/../Tabs.php';

// Подключаем все блоки
require_once __DIR__ . '/NoAccessBlock.php';
require_once __DIR__ . '/StartFourBlock.php';
require_once __DIR__ . '/car_register/CarRegisterBlock.php';
require_once __DIR__ . '/personal_files/PersonalFilesBlock.php';
require_once __DIR__ . '/mobile_calls/MobileCallsBlock.php';
require_once __DIR__ . '/bank_transactions/BankTransactionsBlock.php';

/**
 * Главный trait для работы с базами данных
 * Использует отдельные блоки для каждого типа базы данных
 */
trait Databases
{
    // Подключаем все блоки
    use NoAccessBlock;
    use StartFourBlock;
    use CarRegisterBlock;
    use PersonalFilesBlock;
    use MobileCallsBlock;
    use BankTransactionsBlock;

    /**
     * Маршрутизатор для различных шагов баз данных
     * @param string $step
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    public function uploadTypeTabsDatabasesStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'no_access':
                $return = $this->uploadDatabasesNoAccess($lang_id);
                break;
                
            case 'databases_start_four':
                $return = $this->uploadDatabasesStartFour($lang_id);
                break;
                
            // Car Register
            case 'databases_start_four_inner_first_car_register':
                $return = $this->uploadDatabasesCarRegister($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_second_car_register_huilov':
                $return = $this->uploadDatabasesCarRegisterHuilov($lang_id, $team_id);
                break;
                
            // Personal Files
            case 'databases_start_four_inner_first_personal_files':
                $return = $this->uploadDatabasesPersonalFiles($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_second_personal_files_private_individual':
                $return = $this->uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_second_personal_files_private_individual_huilov':
                $return = $this->uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_second_personal_files_ceo_database':
                $return = $this->uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_second_personal_files_ceo_database_rod':
                $return = $this->uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id);
                break;
                
            // Mobile Calls
            case 'databases_start_four_inner_first_mobile_calls':
                $return = $this->uploadDatabasesMobileCalls($lang_id, $team_id);
                break;
                
            case 'databases_start_four_inner_first_mobile_calls_messages':
                $return = $this->uploadDatabasesMobileCallsMessages($lang_id, $team_id);
                break;
                
            // Bank Transactions
            case 'databases_start_four_inner_first_bank_transactions':
                $return = $this->uploadDatabasesBankTransactions($lang_id, $team_id);
                break;
                
            case 'databases_bank_transactions_success':
                $return = $this->uploadDatabasesBankTransactionsSuccess($lang_id, $team_id);
                break;
                
            default:
                $return = $this->uploadDatabasesNoAccess($lang_id);
                break;
        }

        return $return;
    }
}
