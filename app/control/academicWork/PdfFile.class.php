<?php
class WindowPDFView extends TWindow
{
    public function __construct()
    {
        parent::__construct();
        parent::setTitle(_t('Window with embedded PDF'));
        parent::setSize(0.8, 0.8);
        
        TTransaction::open('works');

        if (isset($_GET['work_id'])) {
            $work_id = $_GET['work_id'];
            $work = AcademicWork::find($work_id);

            if ($work) {
                $file = $work->file;
                $file_data = json_decode(urldecode($file), true);

                if (isset($file_data['fileName']) && file_exists($file_data['fileName'])) {
                    $file_name = $file_data['fileName'];

                    // Configurar os cabeçalhos para o download
                    header('Content-Description: File Transfer');
                    header('Content-type: application/pdf');
                    header('Content-Disposition: inline; filename="' . basename($file_name) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file_name));

                    // Enviar o conteúdo do arquivo como resposta
                    readfile($file_name);
                    exit;
                } else {
                    echo 'Arquivo não encontrado.';
                }
            } else {
                echo 'Trabalho acadêmico não encontrado.';
            }
        } else {
            echo 'ID do trabalho acadêmico ausente ou inválido.';
        }

        TTransaction::close();

        // Caso não seja possível exibir o PDF embutido, oferecer link para download
        $link = new TElement('a');
        $link->href = 'https://adianti.com.br/resources/framework/adianti_framework.pdf';
        $link->target = '_blank';
        $link->add('Clique aqui para baixar o arquivo PDF');

        parent::add($link);
    }
}
