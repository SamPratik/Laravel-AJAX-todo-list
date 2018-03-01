<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <title>Laravel AJAX ToDo List</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body>
      <div class="row" style="padding:50px 0px;">
        <div class="col-md-6 col-md-offset-3">
          <div class="alert alert-success alert-dismissable" id="SuccessAlert" style="display:none;">
            <strong>Success!</strong> <span id="SuccessMessage"></span>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              ToDo List Application
              <a data-toggle="modal" data-target="#addTask" onclick="AddModal()"><i style="cursor:mouse;" class="fa fa-plus pull-right" aria-hidden="true"></i></a>
            </div>
            <div class="panel-body">
              <div class="list-group" id="items">
                @foreach ($items as $item)
                  <a href="#" class="list-group-item" data-toggle="modal" data-target="#addTask" onclick="EditModal(this, {{$item->id}});">{{ $item->item_name }}</a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="addTask" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add New Item</h4>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label>Item Name: </label>
                  <input id="AddItemInput" class="form-control" type="text" name="itemName" value="" placeholder="Enter item name to add item" />
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button id="AddItemBtn" onclick="SubmitAddItem();" style="display:inline;" type="button" class="btn btn-primary" data-dismiss="modal">Add Item</button>
              <button id="DeleteBtn" value="" style="display:none;" type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteItem(this.value);">Delete</button>
              <button id="EditBtn" value="" style="display:none;" type="button" class="btn btn-warning" data-dismiss="modal" onclick="EditItem(this.value);">Save Changes</button>
            </div>
          </div>

        </div>
      </div>

      {{ csrf_field() }}

      <script>
        function EditModal(t, itemId) {
          document.getElementById('AddItemBtn').style.display = 'none';
          document.getElementById('DeleteBtn').style.display = 'inline';
          document.getElementById('DeleteBtn').value = itemId;
          document.getElementById('EditBtn').style.display = 'inline';
          document.getElementById('EditBtn').value = itemId;

          document.getElementById('AddItemInput').value = t.innerHTML;
        }

        function AddModal() {
          document.getElementById('AddItemBtn').style.display = 'inline';
          document.getElementById('DeleteBtn').style.display = 'none';
          document.getElementById('EditBtn').style.display = 'none';

          document.getElementById('AddItemInput').value = '';
        }

        function SubmitAddItem() {
          var ItemName = document.getElementById('AddItemInput').value;
          var token = $("input[name='_token']").val();
          $.post(
            'lists',
            {
              'ItemName': ItemName,
              '_token': token
            },
            function(data) {
              document.getElementById("SuccessAlert").style.display = 'block';
              document.getElementById('SuccessMessage').innerHTML = data;
              $('#items').load(location.href + ' #items');
            }
          );
        }

        function EditItem(itemId) {
          $.ajax({
            url: 'lists/' + itemId,
            type: 'PUT',
            data: {
              itemId: itemId,
              itemName: $("#AddItemInput").val(),
              '_token': $("input[name='_token']").val()
            }
          })
          .done(function(data) {
            document.getElementById("SuccessAlert").style.display = 'block';
            document.getElementById('SuccessMessage').innerHTML = data;
            $('#items').load(location.href + ' #items');
          })
          .fail(function(data) {
            document.getElementById("SuccessAlert").style.display = 'block';
            document.getElementById('SuccessMessage').innerHTML = 'Something went wrong!';
          });

        }

        function DeleteItem(itemId) {
          $.ajax({
            url: 'lists/'+itemId,
            type: 'DELETE',
            data: {
              itemId: itemId,
              '_token': $("input[name='_token']").val()
            },
          })
          .done(function(data) {
            document.getElementById("SuccessAlert").style.display = 'block';
            document.getElementById('SuccessMessage').innerHTML = data;
            $('#items').load(location.href + ' #items');
          })
          .fail(function(data) {
            document.getElementById("SuccessAlert").style.display = 'block';
            document.getElementById('SuccessMessage').innerHTML = 'Something went wrong!';
          });

        }
      </script>
    </body>
</html>
