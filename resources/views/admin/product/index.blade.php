

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{route('product.create')}}" class="btn btn-primary">Add Product</a>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Product Price</th>
                            <th scope="col">Product Quantity</th>
                            <th scope="col">Description</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $key => $item)
                            <tr>
                                <th scope="row">{{$key + 1}}</th>
                                <td>{{$item->product_name}}</td>
                                <td>{{$item->product_price}}</td>
                                <td>{{$item->product_quantity}}</td>
                                <td>{{$item->description}}</td>
                                <td>
                                    <a href="{{route('product.edit', $item->id)}}" class="btn btn-primary">Edit</a>
                                    <form action="{{route('product.destroy', $item->id)}}" method="post">   
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                              </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Data data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                      </table>
                      {{$products->links()}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>