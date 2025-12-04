<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Report List</title>
    <link rel="stylesheet" href="{{ asset('modules/prism.min.css') }}">
    <script src="{{ asset('modules/jquery.min.js') }}"></script>
    <script src="{{ asset('modules/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap2.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap5.min.js') }}"></script>
    <script src="{{ asset('modules/alpine.min.js') }}" defer></script>
    <script src="{{ asset('modules/popper.min.js') }}"></script>
    <script src="{{ asset('modules/prism.js') }}"></script>
    <script src="{{ asset('modules/prism.min.js') }}"></script>
    <script src="{{ asset('modules/prism-json.min.js') }}"></script>
    <script src="{{ asset('modules/cdn.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('modules/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/bootstrap2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/bootstrap3.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.min.css">
    <script src="{{ asset('js/modalVisibility.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/container.css') }}">
    @routes
</head>

<body>
    <div class="container mt-5">
        <div class="flex justify-between items-center bg-gray-200 p-5 rounded-md">
            <h1>View Reports</h1>
            <div class="text-right"><a href="{{ url('create-report') }}" class="btn btn-primary mb-3">Add New</a></div>
            <table class="table text-left">
                <thead>
                    <tr>
                        @if (count($reports) > 0)
                            @foreach ($reports[0] as $attribute => $value)
                                <th>
                                    {{ ucfirst($attribute) }}</th>
                            @endforeach
                            <th>Action
                            </th>
                        @else
                            <th>No data
                                available
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($reports) > 0)
                        @foreach ($reports as $report)
                            <tr>
                                @foreach ($report as $attribute => $value)
                                    <td>
                                        @if ($attribute === 'report_details' || $attribute === 'users')
                                            @if ($attribute === 'report_details')
                                                <button type="submit" class="btn btn-warning text-white btn-sm"
                                                    data-toggle="modal" data-target="#myModal"
                                                    onclick="getJSON('{{ $value }}','{{ $attribute }}')">JSON
                                                </button>
                                            @else
                                                @php
                                                    $jsonData = json_decode($value);
                                                    if (empty($jsonData)) {
                                                        echo 'No users';
                                                    } else {
                                                        $firstValue = reset($jsonData);
                                                        $size = strlen($firstValue);
                                                        if ($size > 8) {
                                                            $firstValue = substr($firstValue, 0, 8);
                                                        }
                                                        echo $firstValue . ' ...';
                                                    }
                                                @endphp
                                                <a type="submit" class="text-primary" data-toggle="modal"
                                                    data-target="#myModal"
                                                    onclick="getJSON('{{ $value }}','{{ $attribute }}')">
                                                    @if ($value !== '[]')
                                                        more
                                                    @endif
                                                </a>
                                            @endif
                                            <div class="modal" id="myModal">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 id="titleOfModal" class="modal-title"
                                                                style="text-transform: capitalize;">
                                                            </h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal Body -->
                                                        <div class="modal-body">
                                                            <!-- Use a <pre> tag for preformatted text and apply some styling -->
                                                          <pre id="jsonDisplay" class="language-json"></pre>
                                                        </div>
                                                  
                                                        <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($attribute === 'created_at' || $attribute === 'updated_at')
                                                    <?php $firstWord = strtok($value, ' '); ?>
                                                    {{ $firstWord }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <a href="{{ url('/view-report/' . $report->id) }}" title="View Report Data"
                                        class="btn btn-info btn-sm">View</a>
                                    <a href="{{ url('view-report/' . $report->id . '/edit') }}"
                                        class="btn btn-warning text-white btn-sm">Edit</a>
                                    <a href="{{ url('view-report/' . $report->id . '/delete') }}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this report?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

{{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script> --}}
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

{{-- @include('reportList.modal') --}}