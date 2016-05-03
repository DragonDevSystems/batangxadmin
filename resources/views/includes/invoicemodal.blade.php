<div class="modal fade" tabindex="-1" role="dialog" id="mdl_invoice" style="opacity:1.0 !important;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Invoice Information</h4>
      </div>
      <div class="modal-body">


           <div class="row invoice-info">
            <div class="col-sm-8  invoice-col cust-info"></div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col inv-info"></div>
            <!-- /.col -->
            </div>

              <div class="row">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Qty</th>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody id="tbodyList"></tbody>
              </table>
            </div>
          <!-- /.col -->
          </div>
            <div class="row">
              <!-- /.col -->
              <div class="col-xs-12">
                <!--<p class="lead">Amount Due 2/22/2014</p>-->
                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th>No. Items:</th>
                      <td id="tdQty">0</td>
                    </tr>
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td id="tdPrice">0</td>
                    </tr>
                    <tr>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="col-xs-6 action-btn">
                <!--<p class="lead">Amount Due 2/22/2014</p>-->
              </div>
              <!-- /.col -->
            </div>

        </div>
      </div>
       
    </div>

    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
function cancelReservation(cus_id,inv_id)
{
  var type = 3;
   promptConfirmation("Are you sure you want to cancel this invoice?");
      $('#btnYes').click(function() {
        var _token = "{{ csrf_token() }}";
        $.post('{{URL::Route('cancelledReservation',[0,0,0])}}',{ _token: _token , cus_id: cus_id, inv_id: inv_id , type: type},function(response)
      {
        if(response.length != 0)
        {
          promptMsg(response.status,response.message)
        }
      });
    });
    return false;
}

function checkOut(cus_id,inv_id)
{
  promptConfirmation("Are you sure you want to checkout this invoice?");
      $('#btnYes').click(function() {
        var _token = "{{ csrf_token() }}";
        $.post('{{URL::Route('checkoutReservation')}}',{ _token: _token , cus_id: cus_id, inv_id: inv_id},function(response)
      {
        if(response.length != 0)
        {
          promptMsg(response.status,response.message)
        }
      });
      });
      return false;
}
</script>