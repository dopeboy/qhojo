<script src="/js/document/index.js"></script>
<link rel="stylesheet" href="/css/document/index.css">

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
                 <h3>Search</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Search for the gear you want.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Reserve</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Select a rental duration and pickup time for your gear. Send any questions to the lender.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Shoot</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Meet the lender, pick up the gear, and start shooting your favorite pictures.</p>
             </div>                               
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
                Save money by renting gear at cheaper rates than what existing rental stores have to offer.
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
                 <h3>List</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Specify a rental rate, a deposit value, and toss in some descriptive details. Then post it (for free!).</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Approve</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                <p>Select, vet, and approve a rental request. The approved borrower will setup a meeting time with you to pick up the gear.</p>
             </div>                               
         </div>
         <div class="span4 hiwstep">
             <div class="text-center top">
                 <h3>Profit</h3>
             </div>                          
             <div class="text-center middle">
                 <img src="img/shaq.jpg" class="img-circle">
             </div>      
             <div class="bottom text-center">
                 <p>Once the borrower has returned the gear to you, we'll send you the money you are due minus a <a href="/document/fees">small commission</a>.</p>
             </div>                               
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
                If your borrower doesn't return the gear, you will be compensated for the full value of the item subject to Qhojo's insurance policy.
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
                If your gear is damaged, we'll supply you with a replacement and get the gear repaired for you subject to Qhojo's insurance policy.
            </div>
        </div>            
    </div>  

   <div class="text-center">
       <form action="/item/post" method="get">
            <button class="rentlink btn btn-large btn-primary" type="submit">Lend >></button>
       </form>
   </div>                     
</div>            