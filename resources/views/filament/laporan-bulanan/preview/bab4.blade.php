<div
    style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
    ">

    <div style="
            text-align:center;
            margin-bottom:40px;
        ">

        <div
            style="
                font-size:22px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            BAB IV

        </div>

        <div
            style="
                margin-top:10px;
                font-size:18px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            EVALUASI PEMBINAAN

        </div>

    </div>

    <h3>
        A. Evaluasi
    </h3>

    <div style="
            text-align:justify;
            line-height:1.8;
        ">

        {!! $record->evaluasi !!}

    </div>

    <br>

    <h3>
        B. Kendala
    </h3>

    <div style="
            text-align:justify;
            line-height:1.8;
        ">

        {!! $record->kendala !!}

    </div>

    <br>

    <h3>
        C. Solusi
    </h3>

    <div style="
            text-align:justify;
            line-height:1.8;
        ">

        {!! $record->solusi !!}

    </div>

</div>
