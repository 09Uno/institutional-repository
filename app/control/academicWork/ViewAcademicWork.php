<?php
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Template\THtmlRenderer;

class ViewAcademicWork extends TPage
{
    private $html;
    private $workId;

    public function __construct($param)
    {
        parent::__construct();
        
        if (isset($param['workId'])) {
            $this->workId = $param['workId'];
        } else {
            throw new Exception('ID do trabalho não especificado.');
        }
        //$this->workId = 1;
        
        try {
            TTransaction::open('works');
            $con = TTransaction::get();
            $result = $con->query("SELECT * FROM academics_works WHERE id = {$this->workId}");
            $academicWork = $result->fetch();
            TTransaction::close();
            
            if ($academicWork) {
                $html = new THtmlRenderer('app/resources/view-academic-work.html');
                
                // Get the list of advisors and authors for each academic work
                $authors = explode(',', $academicWork['author']);
                $advisors = explode(',', $academicWork['advisor']);
 
                $replaces = [
                    'title' => $academicWork['title'],
                    'date' => $academicWork['presentation_date'],
                    'keywords' => $academicWork['keywords'],
                    'abstract' => $academicWork['abstract'],
                    //'file' => $academic_work['file'],
                    'file-label' => 'Arquivo PDF',
                    //'work_id' => $work_id,
                ];

                $advisorReplace = array_map(function ($advisor) {
                    return ['advisor' => $advisor];
                }, $advisors);

                $authorReplace = array_map(function ($author) {
                    return ['author' => $author];
                }, $authors);

                $html->enableSection('main', $replaces);
                $html->enableSection('advisor', $advisorReplace);
                $html->enableSection('author', $authorReplace);

                $html->enableSection('main', $replaces);
                
                $vbox = new TVBox;
                $vbox->style = 'width: 100%';
                $vbox->add($html);
                parent::add($vbox);
            } else {
                throw new Exception('Trabalho acadêmico não encontrado.');
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}    