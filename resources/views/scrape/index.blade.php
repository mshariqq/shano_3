@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6 ">
        <h2 class="float-left">Scrape Products <i class="fa fa-blind" aria-hidden="true"></i> </h2>

    </div>
    <div class="col-md-6">
        <p class="text-right text-end">
          <!-- Modal trigger button -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalId">
            New Products
          </button>

          <!-- Modal Body -->
          <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
          <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
                <form action="{{route('scrape.store')}}" method="POST" class="modal-content" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="modalTitleId">Create New Scrape Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                          <label for="" class="form-label">Product Name</label>
                          <input type="text" name="name" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">Only text & words</small>
                        </div>
                        {{-- new form group for tagline --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product Tagline</label>
                          <input type="text" name="tagline" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">Something about it</small>
                        </div>
                        {{-- new form groiup for thumbnail picture --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product Thumbnail</label>
                          <input type="file" name="thumbnail" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">JPG, PNG, JPEG</small>
                        </div>
                        {{-- new form group for product link --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product Link</label>
                          <input type="text" name="link" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">Link to the product</small>
                        </div>
                        {{-- new form group for product images multiple --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product Images</label>
                          <input type="file" multiple name="images[]" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">Multiple images, separated by comma</small>
                        </div>
                        {{-- form group for product file --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product File</label>
                          <input type="file" name="file" id="" class="form-control" placeholder="" aria-describedby="helpId">
                          <small id="helpId" class="text-muted">ZIP, RAR</small>
                        </div>
                        {{-- form group for product description --}}
                        <div class="mb-3">
                          <label for="" class="form-label">Product Description</label>
                          <textarea class="form-control" name="description" id="" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
          </div>


          <!-- Optional: Place to the bottom of scripts -->
          <script>
            const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)

          </script>
        </p>
    </div>
    <div class="col-md-12">
        <div class="row">
            @php
                // new shanow app
                $shano = new \App\Shano();
            @endphp
            @foreach ($data['products'] as $item => $product)
                {{-- ignore starting 2 items --}}
                @if ($item == 0 || $item == 1)
                    <div class="col-md-12">
                        <a href="http://">{{$product}}</a>
                    </div>
                @else
                @php
                    // new scrapeProduct
                    $scrapeProduct = new \App\ScrapeProducts($product);
                @endphp
                   <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow">
                        <img class="card-img-top" src="{{$scrapeProduct->thumbnail()}}" alt="">
                        <div class="card-body">
                          <h4 class="card-title font-weight-bold">
                            <a target="__blank" href="{{route('scrape.show', $product)}}" class="nav-link ">{{$scrapeProduct->name()}}</a>
                          </h4>
                          <p class="text-primary mb-0">
                            {{$scrapeProduct->tagline()}}
                          </p>
                         <span>
                            {{-- created_at --}}
                            {{$scrapeProduct->created_at()}}
                         </span>
                        </div>
                      </div>
                   </div>
                @endif
            @endforeach
        </div>

    </div>
</div>
@endsection
