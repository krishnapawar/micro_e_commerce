<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-md-4">
                                <div class="card" style="width: 18rem;">
                                    @if (count($product->productFiles) > 0)
                                        <img class="card-img-top"
                                            src="{{ asset($product->productFiles[0]->file_path) }}" alt="Card image cap"
                                            style="height: 165px; width: 100%;">
                                    @else
                                        <img class="card-img-top" src="" alt="Card image cap">
                                    @endif

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->product_name ?? '' }}</h5>
                                        <p class="card-text">{{ str($product->description ?? '')->limit(100) }}</p>
                                        <p class="card-text price">{{ $product->product_price  ?? '' }}</p>
                                        <input type="hidden" name="proid" value="{{ $product->id  ?? '' }}">
                                        <input type="hidden" name="price" value="{{ $product->product_price  ?? '' }}">
                                        <div class="flex ml-1">
                                        <div class="addCard ml-1">
                                            <button type="button" class="btn btn-primary" >+</button>
                                        </div>
                                        <div>
                                            <span class="ml-1" id="spanVale{{ $product->id  ?? '' }}">0</span>
                                        </div>
                                        <div class="removeCard mr-1">
                                            <button type="button" class="btn btn-warning" >-</button>
                                        </div>
                                        <div class=" mr-1 ">
                                            Total Price:- <span class="ml-1" id="priceTota{{ $product->id  ?? '' }}">0</span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    No products found
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        
        <script>
            $(document).ready(function(){
                let data = localStorage.getItem('card');
                if(data) JSON.parse(data).forEach((i)=>{
                    $("#spanVale"+i.id).text(i.quantity)
                    
                    if(i.quantity >=1 ){
                        $("#priceTota"+i.id).text(i.totalPrice)
                        $("#priceTota"+i.id).removeClass('d-none')
                        
                    }else{
                        $("#priceTota"+i.id).addClass('d-none')
                    }
                });
                    
                $(".addCard").on("click", function(){
                    let quantity = 1;
                    var findData = $(this).closest('.card-body');
                    let proid =  findData.find('input[name="proid"]').val();
                    let price =  findData.find('input[name="price"]').val();
                    let data = localStorage.getItem('card');
                    if(data){
                        data = JSON.parse(data);

                        if (data.find( i => i['id'] == proid )) {
                            data.forEach(function(item){
                                if(item['id'] == proid){
                                    item['quantity'] = item['quantity'] + 1;
                                    item['totalPrice'] = item['quantity'] * item['price'];
                                    $("#spanVale"+proid).text(item['quantity']);
                                    $("#priceTota"+proid).text(item['totalPrice']);
                                    }
                                });
                        
                            localStorage.setItem('card',JSON.stringify(data));
                        } else {
                            totalPrice = quantity*price,
                            data.push({id:proid,price:price,totalPrice:totalPrice,quantity:quantity});
                            localStorage.setItem('card',JSON.stringify(data));
                            $("#spanVale"+proid).text(quantity);
                            $("#priceTota"+proid).text(price);
                            $("#priceTota"+proid).removeClass('d-none')
                        }
                        
                    }else{
                        data = [];
                        totalPrice = quantity*price,
                        data.push({id:proid,price:price,totalPrice:totalPrice,quantity:quantity});
                        localStorage.setItem('card',JSON.stringify(data));
                        $("#spanVale"+proid).text(quantity);
                        $("#priceTota"+proid).text(price);
                        $("#priceTota"+proid).removeClass('d-none')
                    }
                });

                $(".removeCard").on("click", function(){
                    let quantity = 1;
                    var findData = $(this).closest('.card-body');
                    let proid =  findData.find('input[name="proid"]').val();
                    let price =  findData.find('input[name="price"]').val();
                    let data = localStorage.getItem('card');
                    
                    if(data){
                        data = JSON.parse(data);

                        if (data.find( i => i['id'] == proid )) {
                            console.log("in");
                            data.forEach(function(item){
                                if(item['id'] == proid){
                                    item['quantity'] = item['quantity'] - 1;
                                    if(item['quantity'] <= 0){
                                        removeByAttr(data, 'id', proid);
                                        $("#spanVale"+proid).text('0');
                                        $("#priceTota"+proid).addClass('d-none')
                                    }else{
                                        item['totalPrice'] =  item['totalPrice'] -item['price'];
                                        $("#spanVale"+proid).text(item['quantity']);
                                        $("#priceTota"+proid).text(item['totalPrice']);
                                    }
                                    
                                    }
                                });
                            localStorage.setItem('card',JSON.stringify(data));
                        }
                        
                    }else{
                        $("#spanVale"+proid).text('0');
                        $("#priceTota"+proid).addClass('d-none')
                    }
                });
            })
            
            var removeByAttr = function(arr, attr, value){
                var i = arr.length;
                while(i--){
                if( arr[i] 
                    && arr[i].hasOwnProperty(attr) 
                    && (arguments.length > 2 && arr[i][attr] === value ) ){ 

                    arr.splice(i,1);

                }
                }
                return arr;
            }
        </script>
    </x-slot>
</x-app-layout>
