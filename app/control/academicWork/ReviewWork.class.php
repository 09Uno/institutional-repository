<?php


class ReviewWork extends TPage
{
    private $html;

    public function __construct()
    {
        parent::__construct();
        TPage::include_css("../app/templates/theme3/css/personalized/general.css");

        TTransaction::open('works');
        $con = TTransaction::get();

        $result = $con->query('SELECT * FROM academics_works');

        $academic_works = [];
        
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
        
            $html = new THtmlRenderer('app/resources/work-view-list.html');
        
            $replaces = [
                'title' => $academic_work->title,
                'date' => $academic_work->presentation_date,
                'keywords' => $academic_work->keywords,
                'file' => $academic_work->file,
                'file-label' => 'Arquivo PDF'
            ];
        
            $advisorReplace = [];
            foreach ($advisors as $advisor) {
                $advisorReplace[] = ['advisors' => $advisor];
            }
        
            $authorReplace = [];
            foreach ($authors as $author) {
                $authorReplace[] = ['authors' => $author];
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
        
            
        TTransaction::close();


    }

    function onAprove()
    {

        $action1 = new TAction(array($this, 'onAction1'));
        $action2 = new TAction(array($this, 'onAction2'));

        $action1->setParameter('parameter', 1);
        $action2->setParameter('parameter', 2);

        new TQuestion('Realmente deseja aprovar a postagem deste trabalho?', $action1, $action2);


    }
    public static function onAction1($param)
    {
        TToast::show('show', 'Ação realizada com sucesso', 'top right', 'far:check-circle');

    }

    public static function onAction2()
    {
        TToast::show('show', 'Ação cancelada', 'top right', 'fas:exclamation-triangle');
    }

    function onDisaprove()
    {
        $action1 = new TAction(array($this, 'onAction1'));
        $action2 = new TAction(array($this, 'onAction2'));

        $action1->setParameter('parameter', 1);
        $action2->setParameter('parameter', 2);

        new TQuestion('Realmente deseja reprovar a postagem do trabalho?', $action1, $action2);
    }
}