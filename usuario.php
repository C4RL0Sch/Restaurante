<?php
class Usuario{
    public $id;
    public $nombre;
    public $usuario;
    public $rol;

    public function __construct($id, $nombre, $usuario, $rol) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->rol = $rol;
    }
}
?>