<?php



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
    
    
    
// trata valor data nascimento

    //remove barras e espaços 
    
    
    
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
            
            
            //atribui nota a faixa etária
        
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
        
        //atribui nota a unidade escolhida
        
        }
    
   

// Retorno ===========================================================


 if ( ! empty($errors)) {

        // Array de retorno caso existam erros
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        
        // Grava dados
        
        // Retorna Sucesso
        
       $data['success'] = true;
       $data['message'] = $data_nascimento;
    
    }
       
echo json_encode($data);

?>