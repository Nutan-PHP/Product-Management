<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Management</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Product Management</h1>
        <div class="row">
            <div class="alert alert-success" role="alert"></div>
        </div>
        <form id="productForm">
            <input type="hidden" id="productId" name="product_id">
            <div class="row mt-5">
                <div class="col-lg-3 col-sm-12">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Enter Product name" id="name" name="name" class="form-control" >
                    <div class="invalid-feedback" id="error_name"></div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <label for="name">Quantity <span class="text-danger">*</span></label>
                    <input type="number" placeholder="Enter Quantity in stock" id="quantity" name="quantity" class="form-control" required>
                    <div class="invalid-feedback" id="error_quantity"></div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <label for="name">Price <span class="text-danger">*</span></label>
                    <input type="number" placeholder="Enter Price per item" id="price" name="price" class="form-control" required>
                    <div class="invalid-feedback" id="error_price"></div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <button type="submit" class="mt-4 btn btn-primary">Save Product</button>
                </div>
            <div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered mt-5">
                <thead>
                    <tr>
                        <th >#</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Total Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="productData"></tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.invalid-feedback').html('').css({'display':'none'});
            $('.alert').css('display','none');
            getProducts();

            //Product Listing
            function getProducts(){
                $.ajax({
                    url: "products",
                    type: 'GET',
                    success:function(response){
                        if (!response.status && 'error' in response){
                            $('.alert').addClass('alert-danger').css('display','block').html(response.data).fadeOut(5000);
                        }else if (response.status && 'data' in response && response.data.length){
                            let rows = "";
                            let sumTotalValue  = 0;
                            $.each(response.data, function(i, product) {
                                let totalValue = product.quantity * product.price;
                                sumTotalValue += totalValue;

                                var jsDate = new Date(product.created_at);
                                var formattedDate = jsDate.toLocaleString();

                                rows += '<tr data-id="'+ product.id +'" data-name="'+ product.name +'" data-quantity="'+ product.quantity +'" data-price="'+ product.price +'">' +
                                        '<td>'+ product.id +'</td>' +
                                        '<td>'+ product.name +'</td>' +
                                        '<td>'+ product.quantity +'</td>' +
                                        '<td>'+ product.price +'</td>' +
                                        '<td>'+ formattedDate +'</td>' +
                                        '<td>'+ totalValue +'</td>' +
                                        '<td><button type="button" class="btn btn-sm btn-warning edit-btn">Edit</button></td>' +
                                    '</tr>';
                            });
                            if(sumTotalValue){
                                rows += '<tr><td colspan="5">Sum Of Total Values</td><td>'+ sumTotalValue +'</td><td></td></tr>';
                            }
                            $("#productData").html(rows);
                        }else{
                            $("#productData").html('<tr><td style="text-align: center;" colspan="7 ">No Records Found</td></tr>');
                        }
                    } 
                });
            }
            

            $("#productForm").submit(function(e) {
                e.preventDefault();
                var productId = $("#productId").val();
                var url = productId ? '/products/' + productId : '/products';
                var type = productId ? 'PUT' : 'POST';
                $.ajax({ 
                    data: $(this).serialize(), 
                    type: type,
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.invalid-feedback').html('').css({'display':'none'});
                        if (!response.status && 'errors' in response){//Validation errors
                            $.each(response.errors, function(key, message) {
                                $('#error_' + key).html(message).css({'display':'block'});
                            });
                        }else if (!response.status && 'error' in response){//Server side error
                            $('.alert').addClass('alert-danger').css('display','block').html(response.data).fadeOut(5000);
                        }else{
                            if(productId){
                                $("#productId").val("");
                            }
                            $("#productForm")[0].reset();
                            getProducts();
                            $('.alert').addClass('alert-success').css('display','block').html(response.message).fadeOut(5000);
                        }
                    }
                });
            }); 

            $(document).on("click", ".edit-btn", function() {
                var currentRow = $(this).closest("tr");console.log($(this).data("id"));
                $("#productId").val(currentRow.data("id"));
                $("#name").val(currentRow.data("name"));
                $("#quantity").val(currentRow.data("quantity"));
                $("#price").val(currentRow.data("price"));
            });
        });
    </script>
</body>
</html>