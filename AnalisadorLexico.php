<?php
    require_once "Automato.php";
    require_once "Token.php";

    class AnalisadorLexico{

        private $automato;
        private $tokensAceitos = [
            // Identificadores
            'q1' => 'ID', 
            
            // Palavras reservadas
            'q3' => 'SE', 'q6' => 'SENAO', 'q10' => 'FACA', 'q18' => 'ENQUANTO', 'q24' => 'ESCREVA', 'q31' => 'IMPRIMA', 'q34' => 'VAR', 'q38' => 'LEIA',
            'q42' => 'LEIA', 'q49' => 'ESCREVA', 'q56' => 'ENQUANTO', 'q58' => 'SE', 'q61' => 'SENAO', 'q65' => 'FACA', 'q72' => 'IMPRIMA', 'q75' => 'VAR',

            // Constantes
            'q76' => 'CONST',

            // Operadores
            'q77' => 'ABRE_PARENTESES', 'q78' => 'FECHA_PARENTESES', 'q79' => 'ABRE_COLCHETES', 'q80' => 'FECHA_COLCHETES', 'q81' => 'ABRE_CHAVES', 'q82' => 'FECHA_CHAVES', 'q83' => 'VIRGULA', 'q84' => 'PONTO', 'q85' => 'PONTO_VIRGULA', 
            'q86' => 'ASPAS_DUPLAS', 'q87' => 'NEGACAO', 'q88' => 'MULTIPLICACAO', 'q89' => 'ADICAO', 'q90' => 'SUBTRACAO', 'q91' => 'DIVISAO', 'q92' => "RESTO_DIVISAO", 'q93' => 'ATRIBUICAO', 'q94' => 'IGUALDADE', 'q95' => 'AND', 'q99' => 'ESPACO'
        ];

        public function __construct(){
            $this->automato = new Automato();
        }

        // Função que realiza a Análise Léxica do código

        public function executar($entrada){
            // Inicialização das variáveis

            $tokens = [];
            $erros = [];
            
            $linha = 1;
            $coluna = 0;

            $estadoAtual = "q0";
            $lexema = "";

            // Início da leitura do código

            for($i = 0; $i < strlen($entrada); $i++){
                if($entrada[$i] == "\r"){
                    $linha++;
                    $coluna = 0;
                    $i++;
                } else{
                    $coluna++;

                    $proximoCaracter = isset($entrada[$i + 1]) ? $entrada[$i + 1] : null;

                    $lexema .= $entrada[$i];

                    if(isset($this->automato->transicoes[$estadoAtual][$entrada[$i]]))
                        $estadoAtual = $this->automato->transicoes[$estadoAtual][$entrada[$i]];

                    if(!isset($this->automato->transicoes[$estadoAtual][$proximoCaracter]) && $estadoAtual != "q0"){
                        if($estadoAtual != 'q99'){ // Se o estado atual for o q99, o token de espaços não é adicionado ao vetor
                            $nomeToken = isset($this->tokensAceitos[$estadoAtual]) ? $this->tokensAceitos[$estadoAtual] : "ID";

                            $tokens[] = new Token($nomeToken, $lexema, $linha, $coluna);
                        }

                        $estadoAtual = "q0";
                        $lexema = "";
                    } else{
                        if($estadoAtual == "q0"){
                            $erros[] = new Token("", $lexema, $linha, $coluna);
                            $lexema = "";
                        }
                    }
                }
            }

            $vetorTokensErros = [$tokens, $erros];

            return $vetorTokensErros;
        }

    }
?>