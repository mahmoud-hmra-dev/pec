<link rel="stylesheet" href="{{asset('css/admin/admin.css')}}">
<link rel="stylesheet" href="{{asset('css/admin/custom.css')}}">
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
<style>
    /* Card */
    .card {
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }
    .dashboard card {
        margin-bottom: 30px;
        border: none;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .dashboard .card-header,
    .dashboard .card-footer {
        border-color: #ebeef4;
        background-color: #fff;
        color: #798eb3;
        padding: 15px;
    }

    .dashboard .card-title {
        padding: 20px 0 15px 0;
        font-size: 18px;
        font-weight: 500;
        color: #012970;
        font-family: "Poppins", sans-serif;
        float: none;
    }

    .dashboard .card-title span {
        color: #899bbd;
        font-size: 14px;
        font-weight: 400;
    }

    .dashboard .card-body {
        padding: 0 20px 20px 20px;
    }

    .dashboard .card-img-overlay {
        background-color: rgba(255, 255, 255, 0.6);
    }

    /* Alerts */
    .dashboard .alert-heading {
        font-weight: 500;
        font-family: "Poppins", sans-serif;
        font-size: 20px;
    }

    /* Close Button */
    .dashboard .btn-close {
        background-size: 25%;
    }

    .dashboard .btn-close:focus {
        outline: 0;
        box-shadow: none;
    }

    /*--------------------------------------------------------------
    # Dashboard
    --------------------------------------------------------------*/
    /* Filter dropdown */
    .dashboard .filter {
        position: absolute;
        right: 5px;
        top: 15px;
    }

    .dashboard .filter .icon {
        color: #aab7cf;
        padding-right: 20px;
        padding-bottom: 5px;
        transition: 0.3s;
        font-size: 16px;
    }

    .dashboard .filter .icon:hover,
    .dashboard .filter .icon:focus {
        color: #4154f1;
    }

    .dashboard .filter .dropdown-header {
        padding: 8px 15px;
    }

    .dashboard .filter .dropdown-header h6 {
        text-transform: uppercase;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 1px;
        color: #aab7cf;
        margin-bottom: 0;
        padding: 0;
    }

    .dashboard .filter .dropdown-item {
        padding: 8px 15px;
    }

    /* Info Cards */
    .dashboard .info-card {
        padding-bottom: 10px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .dashboard .info-card h6 {
        font-size: 28px;
        color: #012970;
        font-weight: 700;
        margin: 0;
        padding: 0;
    }

    .dashboard .card-icon {
        font-size: 24px;
        line-height: 0;
        width: 64px;
        height: 64px;
        flex-shrink: 0;
        flex-grow: 0;
    }

    .dashboard .sales-card .card-icon {
        color: #4154f1;
        background: #f6f6fe;
    }
    .dashboard .card-icon {
        font-size: 24px;
        line-height: 0;
        width: 64px;
        height: 64px;
        flex-shrink: 0;
        flex-grow: 0;
    }
    .dashboard .nav-icon {
        font-size: 50px;
        color: #f6f6fe;

    }
    .dashboard .revenue-card .card-icon {
        color: #2eca6a;
        background: #e0f8e9;
    }

    .dashboard .customers-card .card-icon {
        color: #ff771d;
        background: #ffecdf;
    }

    /* Activity */
    .dashboard .activity {
        font-size: 14px;
    }

    .dashboard .activity .activity-item .activite-label {
        color: #888;
        position: relative;
        flex-shrink: 0;
        flex-grow: 0;
        min-width: 64px;
    }

    .dashboard .activity .activity-item .activite-label::before {
        content: "";
        position: absolute;
        right: -11px;
        width: 4px;
        top: 0;
        bottom: 0;
        background-color: #eceefe;
    }

    .dashboard .activity .activity-item .activity-badge {
        margin-top: 3px;
        z-index: 1;
        font-size: 11px;
        line-height: 0;
        border-radius: 50%;
        flex-shrink: 0;
        border: 3px solid #fff;
        flex-grow: 0;
    }

    .dashboard .activity .activity-item .activity-content {
        padding-left: 10px;
        padding-bottom: 20px;
    }

    .dashboard .activity .activity-item:first-child .activite-label::before {
        top: 5px;
    }

    .dashboard .activity .activity-item:last-child .activity-content {
        padding-bottom: 0;
    }

    /* News & Updates */
    .dashboard .news .post-item+.post-item {
        margin-top: 15px;
    }

    .dashboard .news img {
        width: 80px;
        float: left;
        border-radius: 5px;
    }

    .dashboard .news h4 {
        font-size: 15px;
        margin-left: 95px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .dashboard .news h4 a {
        color: #012970;
        transition: 0.3s;
    }

    .dashboard .news h4 a:hover {
        color: #4154f1;
    }

    .dashboard .news p {
        font-size: 14px;
        color: #777777;
        margin-left: 95px;
    }

    /* Recent Sales */
    .dashboard .recent-sales {
        font-size: 14px;
    }

    .dashboard .recent-sales .table thead {
        background: #f6f6fe;
    }

    .dashboard .recent-sales .table thead th {
        border: 0;
    }

    .dashboard .recent-sales .dataTable-top {
        padding: 0 0 10px 0;
    }

    .dashboard .recent-sales .dataTable-bottom {
        padding: 10px 0 0 0;
    }

    /* Top Selling */
    .dashboard .top-selling {
        font-size: 14px;
    }

    .dashboard .top-selling .table thead {
        background: #f6f6fe;
    }

    .dashboard .top-selling .table thead th {
        border: 0;
    }

    .dashboard .top-selling .table tbody td {
        vertical-align: middle;
    }

    .dashboard .top-selling img {
        border-radius: 5px;
        max-width: 60px;
    }


    table.dataTable>tbody>tr {
        background-color: white;
    }
    table.table-bordered.dataTable {
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        border: 3px solid #dee2e6;
    }
    .dataTables_filter label {
        position: relative;
    }

    .dataTables_filter label input[type="search"] {
        padding-left: 24px;
        padding-right: 48px; /* Increase padding-right to make space for the text */
        background: url('{{asset('images/search.svg')}}') no-repeat left center, content-box;
        background-position-x: calc(100% - 16px);
        background-size: 16px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border: 1px solid #316A5B;
        border-radius: 16px;
    }

    .dataTables_filter label input[type="search"]::before {
        content: "Search";
        position: absolute;
        right: 12px; /* Change left to right */
        line-height: 32px;
        color: #555;
        font-size: 14px;
        pointer-events: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #316a5b;
    }


</style>
@stack("extra_styles")
