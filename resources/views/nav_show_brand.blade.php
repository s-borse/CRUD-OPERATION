@extends('navigationbar')
@section('content')
<main>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary ml-5 mt-5" data-toggle="modal" data-target="#exampleModal">
    Add Brand
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Brand</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="modalform" method="POST" action="/brands">

            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="">
            </div>
            <div class="form-group">
              <label for="name">Image</label>
              <input type="file" name="image" id="image" class="form-control-file" value="">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary save">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Brand Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="updateform" action="" method="post">

            <input type="hidden" id="brand_id" name="brand_id">

            <div class="form-group">
              <label for="editBrandName">Name</label>
              <input type="text" class="form-control" id="editBrandName" name="editBrandName" required>
            </div>
            <div class="form-group">
              <label for="editBrandImage">Image</label>
              <input type="file" class="form-control" id="editBrandImage" name="editBrandImage">
            </div>
            <!-- You can also add a file input here if you want to upload a new image -->
            <button type="button" class="btn btn-primary update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Brand -->
  <div class="col-sm-12 text-center">
    <h3 class="alert-warning p-2">Show Brands Information</h3>
    <table class="table" id="brandTable">
      <thead>
        <tr>
          <th>Index</th>
          <th>Name</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="tbody">

      </tbody>
    </table>
  </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    // Event handler for the save button
    $('.save').on('click', function () {
      var form = $('#modalform')[0];
      var formData = new FormData(form);

      // Logging FormData entries for debugging
      for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
      }

      // AJAX request to submit form data
      $.ajax({
        url: '/brands',
        method: 'POST',
        data: formData,
        contentType: false, // Required for FormData
        processData: false, // Required for FormData
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          console.log('Success:', response);
          // Call the function to update the brand list after successful submission
          location.reload();
          showbrand();
        },
        error: function (xhr) {
          console.log('Error:', xhr.responseText);
          // Optionally, handle error response here
        }
      });

    });


    function showbrand() {
      $.ajax({
        url: '/show-brand',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') { // Check the status from the response
            $('tbody').empty(); // Clear existing rows
            const baseUrl = '{{ url('/') }}';
            console.log(response);

            $.each(response.data, function (key, item) {
              $('tbody').append(
                '<tr>' +
                '<td>' + item.id + '</td>' +
                '<td>' + item.name + '</td>' +
                '<td><img src="' + baseUrl + '/storage/' + item.image + '" alt="' + item.name + '" style="max-width: 100px; max-height: 100px;"></td>' + '<td><button type="button" value="' + item.id + '" class="btn btn-success editbtn">Edit</button></td>' +
                '<td><button type="button" value="' + item.id + '" class="btn btn-danger deletebtn">Delete</button></td>' +
                '</tr>'
              );
            });

          } else {
            console.log('Error: ', response.message); // Handle errors if needed
          }
        },
        error: function (xhr) {
          console.log('Error fetching brands:', xhr.responseText);
        }
      });
    }
    showbrand();

    $(document).on('click', '.editbtn', function () {
      var brand_id = $(this).val();
      $('#editModal').modal('show');

      $.ajax({
        url: '/edit-brand/' + brand_id,
        method: 'GET',
        success: function (response) {
          // console.log(response.brand.name);
          $('#editBrandName').val(response.brand.name);
          // $('#editBrandImage').val(response.brand.image);
          $('#brand_id').val(brand_id);

        }
      })
    });

    $('.update').on('click', function () {
      var update = $('#updateform')[0];
      var formdata = new FormData(update);

      $.ajax({
        url: '/update-brand',
        method: 'POST',
        data: formdata,
        contentType: false,
        processData: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          // console.log(response.brand.name);
          location.reload();

        },
      });

    });

    $('#tbody').on('click', '.deletebtn', function () {
      // var id = $(this).val();
      var id = $(this).val();
      console.log(id);


      $.ajax({
        url: '/delete-brand',
        method: 'POST',
        data: { id: id },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
          console.log(data);
          location.reload();
        }
      });
    });

  });

</script>
@endsection