<?php

class TransactionModel extends Model 
{    
    public function processDetails($details)
    {
        $transactions = [];
        
        foreach ($details as $key=>$detail)
        {
            if (empty($transactions[$detail["TRANSACTION_ID"]]))
            {
                $transactions[$detail["TRANSACTION_ID"]] = $detail;
            }
            
            $transactions[$detail["TRANSACTION_ID"]]["HIST"][] = array("STATE_B_ID" => $detail["STATE_B_ID"],"SUMMARY" => $detail["SUMMARY"], "ENTRY_DATE" => $detail["ENTRY_DATE"]);
            $transactions[$detail["TRANSACTION_ID"]]["FINAL_STATE_NAME"] = $detail["STATE_NAME"];
            $transactions[$detail["TRANSACTION_ID"]]["FINAL_STATE_ID"] = $detail["STATE_B_ID"];
            
            // Request
            if ($detail["STATE_B_ID"] == 200)
            {
                $data = json_decode($detail['DATA']); 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["START_DATE"] = $data->{"START_DATE"}; 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["END_DATE"] = $data->{"END_DATE"};
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["MESSAGE"] = $data->{"MESSAGE"};                
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["RECEIVED_DATE"] = $detail["ENTRY_DATE"];
                
                $end = new DateTime($data->{"END_DATE"}); 
                $start = new DateTime($data->{"START_DATE"}); 
                $diff = $end->diff($start); 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["TOTAL"] = $diff->d * $detail["RATE"];
            }
        }
        
        return $transactions;
    }
}

?>
