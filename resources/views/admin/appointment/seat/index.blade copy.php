@extends('admin.layouts.app')
@section('links')
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        /* General Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
        }

        .seat-selection-container {
            padding: 40px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.1);
            margin: 40px auto;
            width: 95%;
            max-width: 1100px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .seat-selection-container:hover {
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.2);
            transform: translateY(-8px);
        }

        h1 {
            color: #2d3b45;
            font-size: 36px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        /* Seat Grid */
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 20px;
            justify-items: center;
            padding: 20px;
        }

        /* Each Seat Item */
        .seat-item {
            width: 100px;
            height: 100px;
            background-color: #00bcd4; /* Default color for available seats */
            color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
            position: relative;
            text-align: center;
            border: 3px solid #fff;
        }

        /* Hover Effects */
        .seat-item:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background-color: #0097a7; /* Slightly darker blue on hover */
        }

        /* Booked Seat Styling */
        .booked {
            background-color: #b0bec5; /* Gray for booked seats */
            cursor: not-allowed;
        }

        /* Available Seat Styling */
        .available {
            background-color: #00bcd4; /* Bright cyan for available seats */
        }

        /* Seat Number Styling */
        .seat-number {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Tooltips */
        .seat-item[data-toggle="tooltip"]:hover::after {
            content: attr(title);
            position: absolute;
            top: 110%;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 18px;
            background-color: rgba(0, 0, 0, 0.85);
            color: #fff;
            font-size: 14px;
            border-radius: 8px;
            white-space: nowrap;
            margin-top: 10px;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Search Form */
        .form-group label {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #ddd;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #00bcd4;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #00bcd4;
            border-color: #00bcd4;
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0097a7;
            border-color: #0097a7;
        }
    </style>
@endsection

@section('content')
    <div class="wrapper">
        @include('admin.layouts.nav')
        @include('admin.layouts.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Seat Selection</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Seat Selection</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <form method="GET" action="{{ route('searchSeats') }}">
                                <div class="form-group">
                                    <label for="class_id">Search by Class</label>
                                    <input type="text" class="form-control" id="class_id" name="class_id" placeholder="Enter Class ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>

                            <div class="seat-selection-container">
                                <h1>Seat Points for Class ID: {{ request('class_id') }}</h1>
                                <div class="seat-grid">
                                    @foreach($seatPoints as $seat)
                                        <div class="seat-item {{ in_array($seat->id, $bookedSeats) ? 'booked' : 'available' }}"
                                             data-toggle="tooltip" title="{{ $seat->note }}">
                                            <span class="seat-number">{{ $seat->seat_number }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endsection
