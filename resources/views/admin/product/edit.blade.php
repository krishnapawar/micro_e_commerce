

<x-app-layout>
    <x-slot name="style">
        <style>
            .preview {
                display: inline-block;
                margin: 10px;
            }
            .preview img {
                width: 100px;
                height: 100px;
                margin-right: 10px;
            }
        </style>
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="post" action="{{route('product.update',$product->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for=" product_name">product name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"  placeholder="product name" value="{{old('product_name',$product->product_name)}}">
                                @error('product_name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="product_price">product price</label>
                                <input type="number" class="form-control" id="product_price" name="product_price"  placeholder="product price" value="{{old('product_price',$product->product_price)}}">
                                @error('product_price')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            

                            <div class="form-group col-md-6">
                                <label for="product_quantity">product quantity</label>
                                <input type="number" class="form-control" id="product_quantity" name="product_quantity"  placeholder="product quantity" value="{{old('product_quantity',$product->product_quantity)}}">
                                @error('product_quantity')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for=" description">description</label>
                                <textarea class="form-control" id="description" name="description"  placeholder="description" width="100">{{old('description',$product->description)}}"</textarea>
                                @error('description')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <div id="preview-container">
                                </div>
                                <div id="preview-container-edit">
                                    @foreach ($product->productFiles as $key=>$productFile)
                                        <div class="preview">
                                            <img src="{{asset($productFile->file_path)}}">
                                            <button type="button" class="delete" fdprocessedid="{{ $key + 1 }}0wt6ze" onclick="deletefile('{{$productFile->id}}')">Delete</button>
                                        </div>
                                    @endforeach
                                </div>
                                <label for="file-input">Images</label>
                                <input type="file" class="form-control" id="file-input" name="images[]" multiple>
                                @error('images')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        
        <script>
            $(document).ready(function(){
                $("#file-input").on("change", function(){
                    var files = $(this)[0].files;
                    $("#preview-container").empty();
                    if(files.length > 0){
                        for(var i = 0; i < files.length; i++){
                            var reader = new FileReader();
                            reader.onload = function(e){
                                $("<div class='preview'><img src='" + e.target.result + "'><button class='delete'>Delete</button></div>").appendTo("#preview-container");
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                });
            $("#preview-container").on("click", ".delete", function(){
                    $(this).parent(".preview").remove();
                    $("#file-input").val(""); // Clear input value if needed
                });

                $("#preview-container-edit").on("click", ".delete", function(){
                    $(this).parent(".preview").remove();
                    $("#file-input").val(""); // Clear input value if needed
                });
            });
            
            

            function deletefile(id){
                $.ajax({
                        url: "{{ route('product.destroyfile') }}",
                        type: 'DELETE',
                        data:{
                            id:id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                        console.log(data);
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            }
                    });
                                
                        

            }
        </script>
    </x-slot>
</x-app-layout>