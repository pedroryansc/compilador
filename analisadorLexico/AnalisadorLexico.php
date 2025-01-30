<?php
    require_once __DIR__."/Automato.php";
    
    require_once __DIR__."/Token.php";

    class AnalisadorLexico{

        private $automato;
        private $tokensAceitos = [
            // Identificadores
            'q1' => 'ID', 
            
            // Palavras reservadas
            'q9' => 'PROGRAMA', 'q14' => 'VAR', 'q19' => 'INT', 'q23' => 'CHAR', 'q28' => 'FLOAT', 'q84' => 'FUNCAO', 'q33' => 'ARRAY',
            'q43' => 'LEIA', 'q52' => 'ESCREVA', 'q79' => 'ENQUANTO', 'q54' => 'SE', 'q72' => 'PARA', 'q91' => 'RETORNA',

            // Constantes
            'q35' => 'CONST', 'q94' => 'CONST',

            // Caracteres especiais e Operadores
            'q44' => 'ABRE_PARENTESES', 'q45' => 'FECHA_PARENTESES', 'q38' => 'ABRE_COLCHETES', 'q39' => 'FECHA_COLCHETES', 'q10' => 'ABRE_CHAVES', 'q11' => 'FECHA_CHAVES', 'q37' => 'VIRGULA', 'q34' => 'PONTO_VIRGULA', 
            'q36' => 'ASPA', 'q63' => 'NEGACAO', 'q57' => 'MULTIPLICACAO', 'q55' => 'ADICAO', 'q56' => 'SUBTRACAO', 'q58' => 'DIVISAO', 'q59' => "RESTO_DIVISAO", 'q15' => 'ATRIBUICAO', 'q16' => 'IGUALDADE', 'q60' => 'AND',
            'q62' => 'OR', 'q64' => 'DIFERENCA', 'q65' => 'MAIOR', 'q66' => 'MENOR', 'q67' => 'MAIOR_IGUAL', 'q68' => 'MENOR_IGUAL', 'q99' => 'ESPACO'
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

                    if(!isset($this->automato->transicoes[$estadoAtual][$proximoCaracter]) && $estadoAtual != "q0" && $estadoAtual != "q61"){
                        if($estadoAtual != 'q99'){ // Se o estado atual for o q99, o token de espaços não é adicionado ao vetor
                            $nomeToken = isset($this->tokensAceitos[$estadoAtual]) ? $this->tokensAceitos[$estadoAtual] : "ID";

                            $tokens[] = new Token($nomeToken, $lexema, $linha, $coluna);
                        }

                        $estadoAtual = "q0";
                        $lexema = "";
                    } else{
                        if($estadoAtual == "q0" || $estadoAtual == "q61"){
                            $erros[] = new Token("", $lexema, $linha, $coluna);
                            $lexema = "";

                            if($estadoAtual == "q61")
                                $estadoAtual = "q0";
                        }
                    }
                }
            }

            $vetorTokensErros = [$tokens, $erros];

            return $vetorTokensErros;
        }

    }
?>