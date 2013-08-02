<div id="dashboard" class="sheet">
    <legend>Dashboard</legend>
    <ul class="nav nav-pills">
        <li class="active">
            <a href="#">Lending</a>
        </li>
        <li>
            <a href="#">Borrowing</a>
        </li>
    </ul>
    <div id="requests" class="dashboard-section" style="">
        <h4 class="section-header" style="">
            Requests (1)
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
                <tr>
                    <td>
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img src="/img/7d_small.png" class="thumbnail">
                            </a>
                            <div class="media-body">
                                <h4>
                                    <a href="#" class="media-heading">Canon 7D</a>
                                </h4>
                                <div>
                                    $25 / day
                                </div>
                                <div>
                                    07/29 - 07/30
                                </div>
                                <div>
                                    <span class="label label-info">REQUESTED</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>$25</td>
                    <td><a href="#">Guido L.</a></td>
                    <td>blah blah blah</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn">Menu</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="#">Accept</a></li>
                                <li><a data-toggle="modal" tabindex="-1" href="#myModal">Reject</a></li> <!-- this should link to a reject form in a modal window. It should say "Please tell <requestor> why you reject the request -->
                            </ul>
                            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel">Modal header</h3>
                                </div>
                                <div class="modal-body">
                                   <p>One fine body…</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                    <button class="btn btn-primary">Save changes</button>
                                </div>
                            </div>                            
                        </div>
                    </td>
                </tr>
              </tbody>
        </table>
    </div>
    
    <div id="current-transactions" class="dashboard-section">
        <h4 class="section-header">
            Current Transactions (1)
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
                <tr>
                    <td>
                        <div class="media">
                            <a class="pull-left" href="#">
                              <img src="/img/7d_small.png" class="thumbnail">
                            </a>
                            <div class="media-body">
                                <h4><a href="#" class="media-heading">Canon 7D</a></h4>
                                <div>$25 / day</div>
                                <div>07/23 - 07/28</div>
                                <span class="label label-warning">RESERVED</span>
                            </div>
                        </div>
                    </td>
                    <td>$150</td>
                    <td><a href="#">Guido L.</a></td>
                    <td>
                        <div class="statuses">
                            <i class="icon-calendar icon-2x"></i>
                            <i class="icon-play icon-2x"></i> 
                            <i class="icon-rotate-left muted icon-2x"></i>
                        </div>
                        <ul class="actions-list">
                            <li>Guido exchanged the camera (<em>07/23 8:52PM</em>)</li>
                            <li class="muted">Manish accepted the rental request (<em>07/23  7:34PM</em>)</li>      
                            <li class="muted">Manish accepted the rental request (<em>07/23  7:34PM</em>)</li>      
                        </ul>
                    </td>
                    <td >
                        <div class="btn-group" style="">
                            <button class="btn">Menu</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                              <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                          <li><a tabindex="-1" href="#">Accept</a></li>
                              <li><a tabindex="-1" href="#">Reject</a></li>
                              <li><a tabindex="-1" href="#">Contact</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    

    <div id="past-transactions" class="dashboard-section">
        <h4 class="section-header">
            Past Transactions (1)
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
            <tr>
                <td>
                    <div class="media">
                        <a class="pull-left" href="#">
                          <img src="/img/7d_small.png" class="thumbnail">
                        </a>
                        <div class="media-body">
                                <h4><a href="#" class="media-heading">Canon 7D</a></h4>
                                <div>$25 / day</div>
                                <div>07/23 - 07/28</div>
                                <span class="label label-success">RETURNED</span>
                        </div>
                    </div>
                </td>
                <td>$25</td>
                <td><a href="#">Guido L.</a></td>
                <td>
                    <div class="statuses">
                        <i class="icon-calendar icon-2x" ></i>
                        <i class="icon-play icon-2x" ></i>
                        <i class="icon-rotate-left icon-2x" ></i> 
                        <i class="icon-comment muted icon-2x"></i>
                        <button class="btn leave-feedback pull-right">Leave Feedback</button>
                    </div>

                    <div>    
                        <span data-toggle="collapse" data-target="#demo">
                            <a href="javascript: void(0)" style="text-decoration: none">[+] View History</a>
                        </span>
                    </div>
 
                    <div id="demo" class="collapse in">
                        <ul class="actions-list">
                            <li>Guido exchanged the camera (<em>07/23 8:52PM</em>)</li>
                            <li class="muted">Manish accepted the rental request (<em>07/23  7:34PM</em>)</li>      
                            <li class="muted">Manish accepted the rental request (<em>07/23  7:34PM</em>)</li>      
                        </ul>                        
                    </div>
                </td>
 
            </tr>
          </tbody>
    </table>       
    </div>
        
    
</div>

<link rel="stylesheet" href="/css/user/dashboard.css">
<script src="/js/user/dashboard.js"></script>