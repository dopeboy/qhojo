<?php
    $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
    require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
?>

<div id="dashboard" class="sheet">
    <legend>Dashboard</legend>
    <div class="tabbable">
        <ul class="nav nav-pills ">
            <li class="active">
                <a href="#lending" data-toggle="tab">Lending</a>
            </li>
            <li>
                <a href="#borrowing" data-toggle="tab">Borrowing</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="lending">
                <div class="dashboard-section requests" style="">
                    <h4 class="section-header" style="">
                        Requests (<span id="requests-count"><?php echo count($viewmodel["LENDING"]["REQUESTS"]["OPEN"]) + count($viewmodel["LENDING"]["REQUESTS"]["PENDING"]); ?></span>)
                    </h4>        
                    <table class="table table-bordered requests-table" style="">
                        <thead>
                            <tr>
                              <th class="item">Item</th>
                              <th class="total">Total</th>
                              <th class="user">Requester</th>
                              <th class="message">Message</th>
                              <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $current = 0;
                                $lender_view = 1; ?>
                                <td colspan="5" class="alert alert-error">
                                    <div style="width: 100%" class="text-center">
                                        <strong>Open (<?php echo count($viewmodel["LENDING"]["REQUESTS"]["OPEN"]) ?>)</strong>
                                    </div>
                                </td>                            
                                <?php
                                $transactions = $viewmodel["LENDING"]["REQUESTS"]["OPEN"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); 
                                ?>
                                <td colspan="5" class="alert alert-success">
                                    <div style="width: 100%" class="text-center">
                                        <strong>Pending (<?php echo count($viewmodel["LENDING"]["REQUESTS"]["PENDING"]) ?>)</strong>
                                    </div>
                                </td>                                 
                                <?php 
                                $transactions = $viewmodel["LENDING"]["REQUESTS"]["PENDING"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); ?> 
                        </tbody>
                    </table>
                </div>

                <div class="dashboard-section current-transactions">
                    <h4 class="section-header">
                        Current Transactions (<span id="current-count"><?php echo count($viewmodel["LENDING"]["CURRENT_TRANSACTIONS"]); ?></span>)
                    </h4>        
                    <table class="table table-bordered current-transactions-table">
                        <thead>
                            <tr>
                                <th class="item">Item</th>
                                <th class="total">Total</th>
                                <th class="user">Borrower</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $current = 1;
                                $lender_view = 1;                            
                                $transactions = $viewmodel["LENDING"]["CURRENT_TRANSACTIONS"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/current.php'); ?> 
                        </tbody>
                    </table>
                </div>


                <div class="dashboard-section past-transactions">
                    <h4 class="section-header">
                        Past Transactions (<span id="past-count"><?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) + count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]); ?></span>)
                    </h4>        
                    <table class="table table-bordered past-transactions-table" >
                        <thead>
                            <tr>
                                <th class="item">Item</th>
                                <th class="total">Total</th>
                                <th class="user">Borrower</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td colspan="5" class="alert alert-error">
                                <div style="width: 100%" class="text-center">
                                    <strong>Awaiting your review (<?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) ?>)</strong>
                                </div>
                            </td>
                            <?php 
                                $current = 0;
                                $lender_view = 1;
                                $awaiting_review = 1;                            
                                $transactions = $viewmodel["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?> 
                            
                            <td colspan="5" class="alert alert-success">
                                <div style="width: 100%" class="text-center">
                                    <strong>Completed (<?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]) ?>)</strong>
                                </div>
                            </td>
                                <?php 
                                    $awaiting_review = 0;                                
                                    $transactions = $viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]; 
                                    require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?>                                 
                        </tbody>
                    </table>       
                </div>
            </div>
            
            <div class="tab-pane" id="borrowing">
                <div id="" class="dashboard-section requests" style="">
                    <h4 class="section-header" style="">
                        Requests (<span id="requests-count"><?php echo count($viewmodel["BORROWING"]["REQUESTS"]["OPEN"]) + count($viewmodel["BORROWING"]["REQUESTS"]["PENDING"]); ?></span>)
                    </h4>        
                    <table id="" class="table table-bordered requests-table" style="">
                        <thead>
                            <tr>
                              <th class="item">Item</th>
                              <th class="total">Total</th>
                              <th class="user">Lender</th>
                              <th class="message">Message</th>
                              <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $current = 0;
                                $lender_view = 0; ?>
                                <td colspan="5" class="alert alert-error">
                                    <div style="width: 100%" class="text-center">
                                        <strong>Open (<?php echo count($viewmodel["BORROWING"]["REQUESTS"]["OPEN"]) ?>)</strong>
                                    </div>
                                </td>                            
                                <?php
                                $transactions = $viewmodel["BORROWING"]["REQUESTS"]["OPEN"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); 
                                ?>
                                <td colspan="5" class="alert alert-success">
                                    <div style="width: 100%" class="text-center">
                                        <strong>Pending (<?php echo count($viewmodel["BORROWING"]["REQUESTS"]["PENDING"]) ?>)</strong>
                                    </div>
                                </td>                                 
                                <?php 
                                $transactions = $viewmodel["BORROWING"]["REQUESTS"]["PENDING"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); ?>  
                        </tbody>
                    </table>
                </div>

                <div id="" class="dashboard-section current-transactions">
                    <h4 class="section-header">
                        Current Transactions (<span id="current-count"><?php echo count($viewmodel["BORROWING"]["CURRENT_TRANSACTIONS"]); ?></span>)
                    </h4>        
                    <table id="" class="table table-bordered current-transactions-table">
                        <thead>
                            <tr>
                                <th class="item">Item</th>
                                <th class="total">Total</th>
                                <th class="user">Lender</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $current = 1;
                                $lender_view = 0;                            
                                $transactions = $viewmodel["BORROWING"]["CURRENT_TRANSACTIONS"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/current.php'); ?> 
                        </tbody>
                    </table>
                </div>


                <div id="" class="dashboard-section past-transactions">
                    <h4 class="section-header">
                        Past Transactions (<span id="past-count"><?php echo count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) + count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]); ?></span>)
                    </h4>        
                    <table id="" class="table table-bordered past-transactions-table" >
                        <thead>
                            <tr>
                                <th class="item">Item</th>
                                <th class="total">Total</th>
                                <th class="user">Lender</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td colspan="5" class="alert alert-error">
                                <div style="width: 100%" class="text-center">
                                    <strong>Awaiting your review (<?php echo count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) ?>)</strong>
                                </div>
                            </td>
                            <?php 
                                $current = 0;
                                $lender_view = 0;
                                $awaiting_review = 1;                            
                                $transactions = $viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?> 
                            
                            <td colspan="5" class="alert alert-success">
                                <div style="width: 100%" class="text-center">
                                    <strong>Completed (<?php echo count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]) ?>)</strong>
                                </div>
                            </td> 
                            <?php 
                                $awaiting_review = 0;                                
                                $transactions = $viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]; 
                                require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?>                                                     
                        </tbody>
                    </table>       
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/user/dashboard.css">
<script src="/js/user/dashboard.js"></script>