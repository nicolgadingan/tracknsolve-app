@extends('layouts.app')

@section('page')
    New Ticket
@endsection

@section('content')
<div class="container" id="tk-create-box">
    <div class="card card-body border-round border-forest-light pt-4">
        <div class="mb-2">
            @livewire('tickets-create', ['ticket_id' => $tkey])
        </div>
        <div class="mb-2">
            @livewire('upload-attachment', ['tkey' => $tkey])
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("body").on("change", "select.must-select", function() {
                var thisObj = $(this);
                if (thisObj.children("option:selected").val() == "") {
                    thisObj.addClass("is-invalid");
                } else {
                    thisObj.removeClass("is-invalid");
                }
            });

            $("body").on("input", "#tk-assignment", function() {
                var thisObj = $(this);
                var inpAsgn = $("#tk-assignee");
                if (thisObj.val() == "") {
                    inpAsgn.val("");
                }
            });

            $("body").on("click", "#tk-create-submit", function() {
                $("#tk-create-form").submit();
            });

            // Delete attachment
            $("body").on("click", ".tk-del-att", function() {
                var attid   =   $(this).data('value'),
                    tarBtn  =   $("#tk-delatt-btn"),
                    tarInp  =   $("#tk-delatt-id");

                // $("#tk-delatt-id").val(attid, function() {
                //     tarBtn.trigger("click");
                // });

                tarInp.val(attid, function() {
                    tarBtn.trigger("click");
                    console.log("Trigger completed.");
                });
            });

            $("body").on("change", "#tk-assignment", function() {
                var grpId   =   $(this).val();
                $("#tk-selected-group").val(grpId);
            });
        });
    </script>
</div>
@endsection