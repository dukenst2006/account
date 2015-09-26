<div class="row">
    <div class="col-md-2">
        Program
    </div>
    <div class="col-md-10 p-b-10">
        <h4 class="semi-bold no-margin">
            {{ $program->name }}
            <a href="/register/program" style="font-size:12px;font-weight:normal" id="program-change">(Change)</a>
        </h4>
        <p>{{ $program->description }}</p>
    </div>
</div>