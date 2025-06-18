<script src="{{url('assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{url('assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{url('assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- apps -->
<!-- apps -->
<script src="{{url('assets/js/app-style-switcher.js')}}"></script>
<script src="{{url('assets/js/feather.min.js')}}"></script>
<script src="{{url('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{url('assets/js/sidebarmenu.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{url('assets/js/custom.min.js')}}"></script>
<!--This page JavaScript -->
<script src="{{url('assets/extra-libs/c3/d3.min.js')}}"></script>
<script src="{{url('assets/extra-libs/c3/c3.min.js')}}"></script>
<script src="{{url('assets/libs/chartist/dist/chartist.min.js')}}"></script>
<script src="{{url('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script src="{{url('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{url('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    const quill = new Quill('#editor', {
      theme: 'snow'
    });

  </script>
  <script src="{{url('assets/js/pages/dashboards/dashboard1.js')}}"></script>
<script src="{{ url('assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/extra-libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
      $('.dataTable').DataTable();
   </script>

  @stack('scripts')
