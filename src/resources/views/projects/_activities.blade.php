<div id="mySidenav" class="sidenav bg-white shadow">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    @foreach (auth()
        ->user()
        ->activities()->get()->take(10)
    as $activity)
        @if ($activity->activitable_type === 'App\Models\Project')
            <a href="{{ route('project.show', $activity->activitable_id) }}" class="text-decoration-none">
            @elseif ($activity->activitable_type=== "App\Models\Task")
                <a href="{{ route('project.show', $activity->activitable->project) }}" class="text-decoration-none">
                    <span
                        class="font-weight-bolder mr-auto">{{ Str::limit($activity->activitable->body, 10, '...') }}</span>
        @endif
        <span> {{ $activity->description }} </span> By <span>{{ $activity->getOwner->name }}</span> <br />
        <span class="text-muted mr-auto">{{ $activity->updated_at->diffForHumans() }}</span>
        @if ($activity->activitable_type === 'App\Models\Project')
            </a>
        @elseif ($activity->activitable_type=== "App\Models\Task")
            </a>
        @endif
    @endforeach
</div>

<!-- Use any element to open the sidenav -->
{{-- <span class="toggleButton position-fixed " title="Open activities panel" onclick="openNav()"><img
        src="{{ asset('images/arrow.svg') }}" class="img-fluid" alt="arrow icon"></span> --}}

@section('css')
    <style>
        .sidenav {
            height: 100%;
            /* 100% Full-height */
            width: 0;
            /* 0 width - change this with JavaScript */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Stay on top */
            top: 0;
            /* Stay at the top */
            left: 0;
            /* Black*/
            overflow-x: hidden;
            /* Disable horizontal scroll */
            padding-top: 60px;
            /* Place content 60px from the top */
            transition: 0.5s;
            /* 0.5 second transition effect to slide in the sidenav */
        }

        /* The navigation menu links */
        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 16px;
            display: block;
            transition: 0.3s;
        }

        /* When you mouse over the navigation links, change their color */
        .sidenav a:hover {
            color: #2a28d4;
        }

        /* Position and style the close button (top right corner) */
        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
        #main {
            transition: margin-left .5s;
            padding: 20px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

    </style>
@endsection

@section('js')
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        /* Set the width of the side navigation to 0 */
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

    </script>
@endsection
