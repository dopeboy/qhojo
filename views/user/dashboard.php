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
                <?php $lender_view = 1; ?>
                <div id="requests" class="dashboard-section" style="">
                    <h4 class="section-header" style="">
                        Requests (<?php echo count($viewmodel["LENDING"]["REQUESTS"]); ?>)
                    </h4>        
                    <table id="requests-table" class="table table-bordered" style="">
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
                             <?php $transactions = $viewmodel["LENDING"]["REQUESTS"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); ?> 
                        </tbody>
                    </table>
                </div>

                <div id="current-transactions" class="dashboard-section">
                    <h4 class="section-header">
                        Current Transactions (<?php echo count($viewmodel["LENDING"]["CURRENT_TRANSACTIONS"]); ?>)
                    </h4>        
                    <table id="current-transactions-table" class="table table-bordered">
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
                            <?php $current = 1; $transactions = $viewmodel["LENDING"]["CURRENT_TRANSACTIONS"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/current.php'); ?> 
                        </tbody>
                    </table>
                </div>


                <div id="past-transactions" class="dashboard-section">
                    <h4 class="section-header">
                        Past Transactions (<?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) + count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]); ?>)
                    </h4>        
                    <table id="past-transactions-table" class="table table-bordered" >
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
                              <?php $current = 0; $awaiting_review = 1; $transactions = $viewmodel["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?> 
                            <td colspan="5" class="alert alert-success">
                                <div style="width: 100%" class="text-center">
                                    <strong>Completed (<?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]) ?>)</strong>
                                </div>
                              <?php $current = 0; $awaiting_review = 0; $transactions = $viewmodel["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?>                                 
                            </td>                        
                        </tbody>
                    </table>       
                </div>
            </div>
            
            <div class="tab-pane" id="borrowing">
                <?php $lender_view = 0; ?>
                <div id="requests" class="dashboard-section" style="">
                    <h4 class="section-header" style="">
                        Requests (<?php echo count($viewmodel["BORROWING"]["REQUESTS"]); ?>)
                    </h4>        
                    <table id="requests-table" class="table table-bordered" style="">
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
                             <?php $transactions = $viewmodel["BORROWING"]["REQUESTS"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/request.php'); ?> 
                        </tbody>
                    </table>
                </div>

                <div id="current-transactions" class="dashboard-section">
                    <h4 class="section-header">
                        Current Transactions (<?php echo count($viewmodel["BORROWING"]["CURRENT_TRANSACTIONS"]); ?>)
                    </h4>        
                    <table id="current-transactions-table" class="table table-bordered">
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
                            <?php $current = 1; $transactions = $viewmodel["BORROWING"]["CURRENT_TRANSACTIONS"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/current.php'); ?> 
                        </tbody>
                    </table>
                </div>


                <div id="past-transactions" class="dashboard-section">
                    <h4 class="section-header">
                        Past Transactions (<?php echo count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]) + count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]); ?>)
                    </h4>        
                    <table id="past-transactions-table" class="table table-bordered" >
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
                              <?php $current = 0; $awaiting_review = 1; $transactions = $viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?> 
                            <td colspan="5" class="alert alert-success">
                                <div style="width: 100%" class="text-center">
                                    <strong>Completed (<?php echo count($viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]) ?>)</strong>
                                </div>
                              <?php $current = 0; $awaiting_review = 0; $transactions = $viewmodel["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"]; require(dirname(dirname(__FILE__)) . '/embeds/dashboard/past.php'); ?>                                 
                            </td>                        
                        </tbody>
                    </table>       
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/user/dashboard.css">
<script src="/js/user/dashboard.js"></script>