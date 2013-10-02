
<link rel="stylesheet" href="/css/document/index.css">

<title>Qhojo - Peer-to-peer platform for borrowing and lending camera and video gear</title>

<div class="sheet headliner">
     
    <h1 style="">Gear in New York City</h1>                             
 
    <div class="row-fluid text-center" style="">    
        <?php require_once(dirname(dirname(__FILE__)) . '/embeds/card.php'); ?>             
     </div>                
</div>

<div class="sheet" id="borrow">
    <h1 class="text-center">How to Borrow</h1>
    <hr>
    <div class="row-fluid">
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Request</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/request.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Search for the gear you want and request to borrow it.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Use</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/use.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Schedule a time and place to pick up the item from the lender. And then rock out with it.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Return</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/return.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Return the gear to the lender.</p>
             </div>                               
         </div>                    
    </div>
    
    <div class='row-fluid' style='margin-top: 20px;'>
        <div class='span12 text-center'>
            <h2>See our <a href='/document/howitworks'>how-it-works guide</a> for more.</h2>
        </div>
    </div>

    <hr class="mini">
    <h1 class="text-center subheader">Benefits</h1>

    <div class="row-fluid benefit">
        <div class="span2 offset1">
            <div class="media text-center">
                <img src="/img/quality.png">
            </div>
        </div>
        <div class="span8">
            <div class="media-body" style="">
                <h4 class="media-heading">Quality</h4>
                Find the highest quality gear from fellow students, professionals, and studio owners across New York City.
            </div>
        </div>            
    </div>    

    <div class="row-fluid benefit">
        <div class="span2 offset1">
            <div class="media text-center">
                <img src="/img/cost.png">
            </div>
        </div>
        <div class="span8">
            <div class="media-body" style="">
                <h4 class="media-heading">Cost</h4>
                Save money by borrowing gear at cheaper rates than what existing rental stores have to offer.
            </div>
        </div>            
    </div>      

   <div class="text-center">
       <form action="/item/search.php" method="get">
            <button class="rentlink btn btn-large btn-primary" type="submit">Borrow >></button>
       </form>       
   </div>                     
</div>

<div class="sheet" id="lend">
    <h1 class="text-center">How to Lend</h1>
    <hr>
    <div class="row-fluid">
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Approve</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/approve.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Select, vet, and approve a borrow request. </p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Lend Out</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/lendout.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>The approved borrower will setup a meeting time with you to pick up your gear.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Get Paid</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="/img/getpaid.gif" class="img-circle">
             </div>      
             <div class="bottom text-center">
                 <p>Once the borrower has returned the gear to you, we'll send you the money you are due minus a <a href="/document/fees">small commission</a>.</p>
             </div>                               
         </div>                    
    </div>

    <div class='row-fluid' style='margin-top: 20px;'>
        <div class='span12 text-center'>
            <h2>See our <a href='/document/howitworks'>how-it-works guide</a> for more.</h2>
        </div>
    </div>
    
    <hr class="mini">
    <h1 class="text-center subheader">Protection</h1>

    <div class="row-fluid benefit">
        <div class="span2 offset1">
            <div class="media text-center">
                <img src="/img/theft.png">
            </div>
        </div>
        <div class="span8">
            <div class="media-body" style="">
                <h4 class="media-heading">Theft</h4>
                If your borrower doesn't return the gear, you will be compensated for the full value of the item as per our <a href="#">damage policy</a>.
            </div>
        </div>            
    </div>    

    <div class="row-fluid benefit">
        <div class="span2 offset1">
            <div class="media text-center">
                <img src="/img/damage.png">
            </div>
        </div>
        <div class="span8">
            <div class="media-body" style="">
                <h4 class="media-heading">Damage</h4>
                If your gear is damaged, you will be compensated all repair costs up to the total amount of the gear as per our <a href="#">damage policy</a>.
            </div>
        </div>            
    </div>  

   <div class="text-center">
       <form action="/item/post" method="get">
            <button class="rentlink btn btn-large btn-primary" type="submit">Lend >></button>
       </form>
   </div>                     
</div>            

<script src="/js/document/index.js"></script>