<?php

namespace Loteria;

use FFI\Exception;

class Loteria
{

    private $dezenas;
    private $resultado;
    private $total;
    private $jogos = array();
    public $_isValid = true;

    public function getDezenas()
    {
        return $this->dezenas;
    }

    public function setDezenas($dezenas)
    {
        $dezenasAceitas = array(6, 7, 8, 9, 10);
        if (!in_array($dezenas, $dezenasAceitas)) {
            throw new Exception('As dezenas devem ser escolhidas entre 6 e 10');
        }
        $this->dezenas = $dezenas;
    }

    public function getResultado()
    {
        return $this->resultado;
    }

    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getJogos()
    {
        return $this->jogos;
    }

    public function setJogos($jogos)
    {
        $this->jogos = $jogos;
    }

    /**
     * Classe construtora para receber os valores de Quantidade Dezena e Total de Jogos. 
     * Caso nao sejam enviados a Quantidade Dezena recebera 6, e o Total de Jogos recebera 1.
     * 
     * @param $qttyDezenas int
     * @param $totalJogos int
     */
    public function __construct(int $qttyDezenas = 6, int $totalJogos = 1)
    {
        try {
            $this->setDezenas($qttyDezenas);
            $this->setTotal($totalJogos);
        } catch (Exception $e) {
            $this->_isValid = false;
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * Classe privada para gerar um jogo baseado no valor de Dezenas passadas no __construct. 
     * 
     * @return $numeros array
     */
    private function gerandoNumeros()
    {
        $numeros = range(01, 60);
        shuffle($numeros);

        $numeros = array_slice($numeros, 0, $this->getDezenas());
        asort($numeros);

        return $numeros;
    }

    /**
     * Classe publica para gerar o numero de jogos passados no Total de Jogos do __construct.
     * Usa a funcao gerandoNumeros() para retornar os numeros de cada jogo.
     * 
     */
    public function gerandoJogos()
    {
        $jogosArr = array();
        for ($i = 0; $i < $this->getTotal(); $i++) {
            $novoJogo = $this->gerandoNumeros();
            array_push($jogosArr, $novoJogo);
        }
        $this->setJogos($jogosArr);
    }

    /**
     * Classe publica para gerar o jogo que e considerado o resultado.
     */
    public function sorteio()
    {
        $this->setResultado($this->gerandoNumeros());
    }

    /**
     * Classe publica para verificar o resultado dos jogos e exibi-los na tela.
     * Usa as funcoes geraLinhas() e geraResultado() para montar a tabela.
     */
    public function confereSorteio()
    {
        $linhas = "";

        foreach ($this->getJogos() as $jogo) {
            $jogoConferido = array_intersect($jogo, $this->getResultado());
            $linhas .= (string) $this->geraLinhas($jogo, $jogoConferido);
        }

        return $this->geraResultado($linhas);
    }

    /**
     * Classe publica para gerar as linhas que serao exibidas na tabela.
     * 
     * @param $jogo array
     * @param $jogoConferido array
     * 
     * @return $linha string
     */
    private function geraLinhas(array $jogo, array $jogoConferido)
    {
        $jogoMontado = (string) implode(" - ", $jogo);
        $acertos = (string) implode(" - ", $jogoConferido);
        $nAcertos = (int) count($jogoConferido);

        $linha = "
        <tr>
            <td>{$jogoMontado}</td>
            <td>{$nAcertos}</td>
            <td>{$acertos}</td>
        </tr>
        ";

        return (string) $linha;
    }

    /**
     * Classe publica para gerar a tabela com o resultado dos jogos.
     * 
     * @param $linhas string
     * 
     * @return $tabela string
     */
    private function geraResultado(string $linhas)
    {
        $resultadoSorteio = implode(" - ", $this->getResultado());
        $tabela = "
            <p><b>Dezenas Sorteadas: {$resultadoSorteio}</b></p>
            <table style='width:100%; font-family: arial, sans-serif; border-collapse: collapse; text-align:center'>
                <tr>
                <th>Dezenas</th>
                <th>Quantidade de Acertos</th>
                <th>Dezenas Acertadas</th>
                </tr>
                {$linhas}
            </table>
        ";
        return $tabela;
    }
}
