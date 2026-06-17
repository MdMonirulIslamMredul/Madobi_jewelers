@php $footer = \App\Models\FooterDetail::latest()->first() @endphp
<footer class="footer">
    © {{date('Y-m-d')}} {{$footer->credit?? 'Designed by Techweb BD IT'}}
</footer>
