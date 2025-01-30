<?php
    class Token{

        private $nome;
        private $lexema;
        private $linha;
        private $coluna;

        public function __construct($nome, $lexema, $linha, $coluna){
            $this->nome = $nome;
            $this->lexema = $lexema;
            $this->linha = $linha;
            $this->coluna = $coluna;
        }

        public function getNome(){
            return $this->nome;
        }

        public function getLexema(){
            return $this->lexema;
        }

        public function getLinha(){
            return $this->linha;
        }

        public function getColuna(){
            return $this->coluna;
        }

    }
?>