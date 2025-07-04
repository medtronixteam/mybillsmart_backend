<!DOCTYPE html>
<html dir="ltr" lang="en">
@include('components.head')
@livewireStyles

<body>
      <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @yield('content')
                    {{@$slot}}

                </div>
            </div>
        </div>  
   @include('components.scripts')
   @livewireScripts
   @stack('scripts')
</body>

</html>
