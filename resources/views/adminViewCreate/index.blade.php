<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Report</title>
    <script type="module" src="{{ asset('js/columnNameFetcher.mjs') }}" defer></script>
    <script type="module" src="{{ asset('js/addTables.mjs') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('modules/prism.min.css') }}">
    <script src="{{ asset('modules/jquery.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('modules/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    @routes
</head>

<body>
    <div class="container center-button">
        <div class="container-bg row">
            <h1 class="mb-4">Create Report</h1>
            <form action="{{ url('/') }}/create-report" method="post">
                @csrf
                <div class="shadow-line p-3 mb-5 rounded">
                    <div>
                        <input type="text" name="name" class="form-control mt-4" id="reportName"
                            placeholder="Report Name" required>
                    </div>
                    <div>
                        <select name="users[]" id="users" class="form-select mt-4 mb-4" multiple>
                            <option disabled value="">Select Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user }}">{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="tablesDiv" class="mb-4">
                    <div id="dynamicDiv0" class="tables shadow-line close-button p-3 mb-5 rounded">
                        <div class="g-3">
                            <label for="table0" class="form-label"></label>
                            <select name="table[]" id="table0" class="form-select mt-0 dynamic"
                                data-dependent="tableColumns0" dependent="tableColumns0" required>
                                <option disabled selected value="">Select Table</option>
                                @foreach ($tableNames as $tableName)
                                    <option value="{{ $tableName }}">{{ $tableName }}</option>
                                @endforeach
                            </select>
                            <label for="tableColumns0" class="form-label"></label>
                            <select name="tables" id="tableColumns0"
                                class="form-select mt-0 mb-4 dynamicdatas tableColumnChanged"
                                data-dependent="tableDatas0" required multiple>
                                <option value="">Select Columns</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="addTableDiv" class="mt-3" type="button">
                    <button id="addTable" class="btn btn-secondary px-2 py-0"> + </button>
                </div>
                <div class="center-button">
                    <button type="submit" class="btn btn-info text-white px-2">Submit</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</body>

</html>