@extends('navigationbar')
@section('content')
<main>

  <button type="button" class="btn btn-primary ml-5 mt-5" data-toggle="modal" data-target="#addModel">
    Add Model
  </button>

  <div class="modal fade" id="addModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Model</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="secodeModalform" method="POST" action="/brand-model">

            <div class="form-group">
              <label for="dropdown">Select Brand</label>
              <select name="brand" id="dropdown" class="form-control">
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
          <option value="{{ $brand->id }}">{{ $brand->name }}</option>
        @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="name">Model Name</label>
              <input type="text" name="model-name" id="model-name" class="form-control" value="">
            </div>
            <div class="form-group">
              <label for="name">Image</label>
              <input type="file" name="image" id="image" class="form-control-file" value="">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary save-change">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Brand -->
  <div class="col-sm-12 text-center">
    <h3 class="alert-warning p-2">Show Brands Models Information</h3>
    <table class="table" id="brandTable">
      <thead>
        <tr>
          <th>Index</th>
          <th>Brand Name</th>
          <th>Model Name</th>
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
    $('.save-change').on('click', function () {
      var ModelForm = $('#secodeModalform')[0];
      var formData = new FormData(ModelForm);

      $.ajax({
        url: '/brand-model', // Ensure this matches your form action
        type: 'POST',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Let jQuery set the content type
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          console.log(response);
          // Handle success (e.g., show a success message or close the modal)
          location.reload();
          modelshowbrand();
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          // Handle error (e.g., show an error message)
          alert('An error occurred: ' + xhr.responseJSON.message || 'Please try again later.');
        }
      });
    });


    function modelshowbrand() {
      $.ajax({
        url: '/model-show-brand',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.error) { // Check for an error in the response
            console.log('Error: ', response.error);
            return; // Exit the function if there's an error
          }

          $('tbody').empty(); // Clear existing rows
          const baseUrl = '{{ url('/') }}'; // Ensure this is available in your view
          console.log(response);

          $.each(response.data, function (key, item) {
            $('tbody').append(
              '<tr>' +
              '<td>' + item.id + '</td>' +
              '<td>' + item.brand_id + '</td>' +
              '<td>' + item.model_name + '</td>' +
              '<td><img src="' + baseUrl + '/storage/' + item.image + '" alt="' + item.model_name + '" style="max-width: 100px; max-height: 100px;"></td>' +
              '<td><button type="button" value="' + item.id + '" class="btn btn-success editbtn">Edit</button></td>' +
              '<td><button type="button" value="' + item.id + '" class="btn btn-danger deletebtn">Delete</button></td>' +
              '</tr>'
            );
          });
        },
        error: function (xhr) {
          console.log('Error fetching brands:', xhr.responseText);
        }
      });
    }
    modelshowbrand();


  });


</script>


@endsection