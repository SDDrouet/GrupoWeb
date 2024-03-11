<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estiloHorarioSemanal.css">
</head>


<?php include('header.php'); ?>
<section class="pt-5">

    <form class="formularios" action="">
        <label class="frmlbl" for="periodo">Periodo</label>
        <label class="frmlbl" for="aula">Aula</label>
        <label  class="frmlbl" for="cursos">Curso</label>
        <select class="selector" name="periodo" id="periodo">
        </select>
        
        <select class="selector" name="aula" id="aula">
            <option class="opcion" value="G301">G301</option>
            <option class="opcion" value="G302">G302</option>
            <option class="opcion" value="G303">G303</option>
        </select>
        
        <select class="selector" name="cursos" id="cursos">

        </select>
    </form>
    <div class="container">
        <div class="cursosSection">
            <div class="tituloDia">INFO CURSO</div>
            <div id="cursosContainer">
                
            </div>
            <div id="trash">ELIMINAR CURSO</div>
        </div>
        <div class="horarioSection">
            <div class="tituloHora">HORAS</div>
            <div class="horasCont">
                <div class="hora">7:00 - 9:00 h</div>
                <div class="hora">9:00 - 11:00 h</div>
                <div class="hora">11:00 - 13:00 h</div>
                <div class="hora">13:30 - 15:30 h</div>
                <div class="hora">15:30 - 17:30 h</div>
                <div class="hora">17:30 - 19:30 h</div>
                <div class="hora">19:30 - 21:30 h</div>
            </div>

        </div>
        <div class="diasSection">
            <div class="tituloDia">LUNES</div>
            <div class="dia" id="lunes"></div>
        </div>
        <div class="diasSection">
            <div class="tituloDia">MARTES</div>
            <div class="dia" id="martes"></div>
        </div>
        <div class="diasSection">
            <div class="tituloDia">MIERCOLES</div>
            <div class="dia" id="miercoles"></div>
        </div>
        <div class="diasSection">
            <div class="tituloDia">JUEVES</div>
            <div class="dia" id="jueves"></div>
        </div>
        <div class="diasSection">
            <div class="tituloDia">VIERNES</div>
            <div class="dia" id="viernes"></div>
        </div>

    </div>

    <script src="../js/crudsDragDrop.js"></script>
    <script src="../js/dragDrop.js"></script>
    <script src="../js/loadHorarios.js"></script>

</section>

<?php include('footer.php'); ?>
