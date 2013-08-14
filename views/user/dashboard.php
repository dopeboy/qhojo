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
                <div id="requests" class="dashboard-section" style="">
                    <h4 class="section-header" style="">
                        Requests (<?php echo count($viewmodel["LENDING"]["REQUESTS"]); ?>)
                    </h4>        
                    <table id="requests-table" class="table table-bordered" style="">
                        <thead>
                            <tr>
                              <th class="item">Item</th>
                              <th class="total">Total</th>
                              <th class="requester">Requester</th>
                              <th class="message">Message</th>
                              <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php $requests = $viewmodel["LENDING"]["REQUESTS"]; require(dirname(dirname(__FILE__)) . '/embeds/request.php'); ?> 
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
                                    <th class="borrower">Borrower</th>
                                    <th class="status">Status</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $transactions = $viewmodel["LENDING"]["CURRENT_TRANSACTIONS"]; require(dirname(dirname(__FILE__)) . '/embeds/current.php'); ?> 
                            </tbody>
                        </table>
                    </div>


                    <div id="past-transactions" class="dashboard-section">
                        <h4 class="section-header">
                            Past Transactions (<?php echo count($viewmodel["LENDING"]["PAST_TRANSACTIONS"]); ?>)
                        </h4>        
                            <table id="past-transactions-table" class="table table-bordered" >
                        <thead>
                            <tr>
                              <th class="item">Item</th>
                              <th class="total">Total</th>
                              <th class="borrower">Borrower</th>
                              <th class="status">Status</th>

                            </tr>
                          </thead>
                          <tbody>
                            <?php $transactions = $viewmodel["LENDING"]["PAST_TRANSACTIONS"]; require(dirname(dirname(__FILE__)) . '/embeds/past.php'); ?> 
                          </tbody>
                    </table>       
                    </div>

        </div>
        <div class="tab-pane" id="borrowing">
            22222222222222
        </div>
    </div>
</div>


    
        
    
</div>

<link rel="stylesheet" href="/css/user/dashboard.css">
<script src="/js/user/dashboard.js"></script>