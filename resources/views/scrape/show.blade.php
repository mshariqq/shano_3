@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{$product->name()}}</h2>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Images & Graphics
            </div>
            <div class="card-body overflow bg-dark">
                <p>
                    <img width="100%" src="{{$product->thumbnail()}}" alt="">
                </p>
                @foreach ($product->images() as $item)
                    <p>
                        <img width="100%" class="shadow" src="{{$item}}" alt="">
                    </p>
                @endforeach
            </div>

        </div>

    </div>
    <div class="col-md-4">
        <ul>
            <li>
                <a href="{{$product->link()}}" target="_blank">Product Link</a>

            </li>
            <li>
                {{-- product tagline --}}
                {{$product->tagline()}}
            </li>
            <li>
                {{-- product created_at --}}
                {{$product->created_at()}}
            </li>
            <li>
                {{-- description --}}
                <textarea readonly class="form-control" name="" id="" cols="30" rows="10">
                    {{$product->description()}}
                </textarea>
            </li>
            <li>
                <b>Zip File</b>
                {{-- product file name --}}
                {{$product->fileName()}}
            </li>

        </ul>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <div class="float-start mb-0">Projects & Versions</div>
                <div class="float-end mb-0">
                    <!-- Modal trigger button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalId">
                      New
                    </button>

                    <!-- Modal Body -->
                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                    <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                        <form action="{{route('scrape.project.create', $product->name())}}" method="POST" class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-dark">
                                    <h5 class="modal-title" id="modalTitleId">Create Project</h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    {{-- form group select dropdown with projects items --}}
                                    <div class="form-group mb-3">
                                        <label for="project_id">Based on which Version?></label>
                                        <select class="form-select" name="base" id="project_id">
                                            <option value="original">Original Version</option>
                                            @foreach ($product->projects() as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- form group input text for project name --}}
                                    <div class="form-group">
                                        <label for="project_name">Project Name</label>
                                        <input type="text" class="form-control" name="name" id="project_name" placeholder="Project Name">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- Optional: Place to the bottom of scripts -->
                    <script>
                        const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)

                    </script>
                </div>
            </div>
            <div class="card-body overflow">
                @php
                    // check count of projects
                    $projects = $product->projects();
                @endphp
                @if (count($projects) > 0)
                    @foreach ($projects as $item)
                        {{-- remove . .. --}}
                        @if ($item != '.' && $item != '..')
                            @php
                            $project_dir = $product->projectPath($item);
                            $code = "cd " .escapeshellarg($project_dir) . "; ls";
                        @endphp
                            <p class="row">

                            <span class="btn btn-primary text-white col-md-4">
                                    {{$item}}
                            </span>
                            <span class="col-md-8">
                                    <input class="form-control" type="text" value="{{$project_dir}}"/>
                            </span>
                                {{-- <a href="{{route('exec', $code)}}" target="_blank">{{$item}}</a> --}}
                            </p>
                        @endif


                    @endforeach
                @else
                    <p>
                        No projects found
                    </p>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
