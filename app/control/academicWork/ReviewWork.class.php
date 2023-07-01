<?php


class ReviewWork extends TPage
{
    private $html;
    
    public function __construct()
    {
        parent::__construct();
        TPage::include_css("../app/templates/theme3/css/personalized/general.css");

        try {
            TTransaction::open('works');
            $con = TTransaction::get();

            $result = $con->query('SELECT * FROM academics_works where isApproved = 0');

            $academic_works = [];

            if ($result != null) {
                foreach ($result as $row) {
                    $academic_works[] = new AcademicWork(
                        $row['id'],
                        $row['title'],
                        $row['author'],
                        $row['advisor'],
                        $row['abstract'],
                        $row['keywords'],
                        $row['presentation_date'],
                        $row['research_area'],
                        $row['file']

                    );
                }


                foreach ($academic_works as $academic_work) {
                    $authors = explode(',', $academic_work->author);
                    $advisors = explode(',', $academic_work->advisor);

                    $authors = explode(',', $academic_work->author);
                    $advisors = explode(',', $academic_work->advisor);

                    // Adicionar números acima dos autores

                    $work_id = $academic_work->id;

                    $html = new THtmlRenderer('app/resources/work-view-list.html');

                    $replaces = [
                        'title' => $academic_work->title,
                        'date' => $academic_work->presentation_date,
                        'keywords' => $academic_work->keywords,
                        'file' => $academic_work->file,
                        'file-label' => 'Arquivo PDF',
                        'work_id' => $work_id,
                    ];

                    $advisorReplace = [];
                    foreach ($advisors as $advisor) {
                        $advisorReplace[] = ['advisors' => $advisor];
                    }

                    $authorReplace = [];
                    foreach ($authors as $author) {
                        $i = array_keys($authors);
                        $authorReplace[] = [
                            'authors' => $author,
                        ];
                    }

                    $html->enableSection('main', $replaces);
                    $html->enableSection('advisors', $advisorReplace, true);
                    $html->enableSection('authors', $authorReplace, true);

                    // Wrap the page content using a vertical box
                    $vbox = new TVBox;
                  
                    $vbox->style = 'width: 100%';
                    $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
                    $vbox->add($html);
                    parent::add($vbox);
                }
                
                
                
                
                
            }

            TTransaction::close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }



    }

    function onApproveClick()
    {
        $work_id = $_GET['work_id'];
        $action1 = new TAction(array($this, 'onAprove'));
        $action2 = new TAction(array($this, 'onAction2'));

        $action1->setParameter('parameter', $work_id);
        $action2->setParameter('parameter', 2);

        new TQuestion('Realmente deseja aprovar a postagem e armazenamento deste trabalho?', $action1, $action2);


    }
    public static function onAprove($param)
    {
        try {

            TTransaction::open('works');
            $work_id = $param['parameter'];
            $work = AcademicWork::find($work_id);

            if ($work != null) {
                $work->isApproved = 1;
                $work->store();
                TToast::show('show', 'Ação realizada com sucesso', 'top right', 'far:check-circle');
            }
            TTransaction::close();

        } catch (Exception $e) {
            TToast::show('error', 'Erro ao realizar ação', 'top right', 'fas:exclamation-triangle');
        }



    }

    public static function onAction2()
    {
        TToast::show('show', 'Ação cancelada', 'top right', 'fas:exclamation-triangle');
    }

    function onDisapproveClick()
    {
        $work_id = $_GET['work_id'];
        $action1 = new TAction(array($this, 'onDisapprove'));
        $action2 = new TAction(array($this, 'onAction2'));

        $action1->setParameter('parameter', $work_id);
        $action2->setParameter('parameter', 2);

        new TQuestion('Realmente deseja reprovar a postagem do trabalho?, <br/> O 
        trabalho será removido do banco de dados!', $action1, $action2);
    }

    function onDisapprove($param)
    {
        try {
            TTransaction::open('works');

            $work_id = $param['parameter'];
            $work = new AcademicWork;
            $work->load($work_id);
            $work->delete();

            TToast::show('show', 'Ação realizada com sucesso', 'top right', 'far:check-circle');

            TTransaction::close();
        } catch (Exception $e) {
            TToast::show('error', 'Erro ao realizar ação', 'top right', 'fas:exclamation-triangle');
        }
    }


}