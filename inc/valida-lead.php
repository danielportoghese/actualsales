<?php

include('conn.php');


// array para verificação de erros

    $errors = array();
    

// array para devolver resposta ao fron-end
    $data   = array();
    


    
// armazenando variaveis

    $nome               = $_POST['nome'];
    
    $data_nascimento    = $_POST['data_nascimento'];
    
    $email              = $_POST['email'];
    
    $telefone           = $_POST['telefone'];
    
    $regiao             = $_POST['regiao'];
    
    $unidade            = $_POST['unidade'];
    
    
// função para limpar strings    
function limpar($string) {
                $string = str_replace(' ', '-', $string); 
                $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); 

                return preg_replace('/-+/', '-', $string); 
            }   
    
    
    
// Validando entradas
    
    
    //Verifica se campo nome foi preenchido 
    
        if (empty($nome)){
            $errors['nome'] = 'Insira o seu nome';
        
        } else {
            
            //Verifica caracteres a presença de caracteres inválidos
            
                if(preg_match( '|^[\pL\s]+$|u', $nome)){
                     
                } else{
                    
                    $errors['nome'] = 'Seu nome não pode conter números ou caracteres especiais';  
                 
                }
            
            //Verfica se foi digitado o nome completo
            
                $nome_quant = str_word_count($nome);
            
                if ($nome_quant >= 2) {
                    
                    //não faz nada
                    
                }else{
                    
                    $errors['nome'] = 'Por favor, digite seu nome e sobrenome';
                    
                }
                    
        }
    
    
    
    
    // Verifica se a data de nascimento foi preenchida
    
        if (empty($data_nascimento)){
            
            $errors['data_nascimento'] = 'Insira a data de nascimento';
       
        }else{
            
            //Verifica se a data é válida
            
            
            //remove caracteres desnecesários
            $data_limpa = limpar($data_nascimento);
            
            $data_quant = strlen($data_limpa);
            
            if($data_quant != 8){
                
                $errors['data_nascimento'] = 'A data de nascimento deve estar no seguinte formato DD/MM/AAAA';
                
                
            }
            
            
            
            //Corrigi data para envio
            list($dia, $mes, $ano) = explode('/', $data_nascimento);
            
            $data_envio = $ano.'-'.$mes.'-'.$dia; 
            
                        
            //atribui nota a faixa etária
            

                
                // Define data de inicio da contagem
                $apartir = mktime(0, 0, 0, '11', '01', '2016');
                
                // Define timestamp da data de nacimento
                $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
                
                // Cálculo da idade 
                $idade = floor((((($apartir - $nascimento) / 60) / 60) / 24) / 365.25);
                
                
                // Define pontuação: 0 pontos entre 18 e 39 anos, -3 pontos entre 40 e 99 ano, -5 pontos se for menor de 18 ou com mais de 100 anos
                
                if ($idade >= 40 AND $idade <= 99){
                  
                    $score_data = 3;
                 
                }
                
                if ($idade < 18 OR $idade >= 100){
                  
                    $score_data = 5;
                 
                }
                
                if ($idade >= 18 AND $idade <= 39){
                  
                    $score_data = 0;
                 
                }
               
              
        
        }
    
        
    
    
    //Verifica se o e-mail foi preenchido    
    
        if (empty($email)){
            
            $errors['email'] = 'Insira o seu email';
            
        }else{
            
            //verifica se o e-mail é válido
            
                $sintaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
                
                if(preg_match($sintaxe,$email)) { 
                    
                    //não faz nada
               
                }else{  
               
                     $errors['email'] = 'Insira um e-mail válido';
               
                }
            
        }
    
    
    
    
    //Verifica se telefone foi preenchido
    
        if (empty($telefone)){
            
            $errors['telefone'] = 'Insira o seu telefone';    
        
        }
    
    
    
    
    //Verifica se uma região foi selecionada
    
        if (empty($regiao)){
    
           $errors['regiao'] = 'Selecione uma região';
    
        }
        
    //Verifica se uma unidade foi selecionada
    
        if (empty($unidade)){
    
            $errors['unidade'] = 'Selecione uma unidade';
    
        }else{
        
            // Atribui Score para Unidade    

            if ($unidade == 'Porto Alegre' OR $unidade == 'Curitiba'){
                $score_unidade = 2;
            }
            
            if ($unidade == 'Rio de Janeiro' OR $unidade == 'Belo Horizonte'){
               $score_unidade = 1;
            }
            
            if ($unidade == 'Salvador' OR $unidade ==  'Recife' ){
                $score_unidade = 4;
            }
            
            if ($unidade == "Brasilia"){
                $score_unidade = 3;
            }
            
            if ($unidade == "INDISPONIVEL"){
                $score_unidade = 5;
            }
            
            if ($unidade == "Sao Paulo"){
               $score_unidade = 0;
            }

        
        }
    
   

// Retorno ===========================================================


 if ( ! empty($errors)) {

        // Array de retorno caso existam erros
        $data['success'] = false;
        $data['errors']  = $errors;
    
    } else {
        
        
        /*Cria score*/
       
        
        
        
        $score = 10 - ((int)$score_data) - ((int)$score_unidade);
        
        
        
        /*Armazena dados no array lead*/
        $lead = array('nome' => $nome,
              'email' => $email,
              'telefone' => $telefone,
              'regiao' => $regiao,
              'unidade' => $unidade,
              'data_nascimento' => $data_envio,
              'score' => $score,
              'token' => '7ee51f14e813ed052bae1ae6bd159b4f'
              
              );
        
        // Grava no banco de dados
        
            $SQL = $MySQLiconn->query("INSERT INTO lead (nome,email,telefone,regiao,unidade,data,score) VALUES ('".$lead['nome']."','".$lead['email']."','".$lead['telefone']."','".$lead['regiao']."','".$lead['unidade']."','".$lead['data_nascimento']."','".$lead['score']."')");
        
            if(!$SQL)
            {
              $data['success'] = false;
              $data['errors']  = $MySQLiconn->error;
            } else {
                  
            /* Faz envio para api*/      
            
             
             $url = 'http://api.actualsales.com.br/join-asbr/ti/lead';
             $ch = curl_init($url);
             
             $postString = http_build_query($lead, '', '&');
             
             curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             
             $response = curl_exec($ch);
             curl_close($ch);
            
            
            
            
             // Retorna Sucesso
             $data['success'] = true;
             $data['message'] = 'Cadastrado no banco com sucesso';
             $data['api'] = $response;   
            }
       
       
       
         
       
       
    
    }
       
echo json_encode($data);




?>