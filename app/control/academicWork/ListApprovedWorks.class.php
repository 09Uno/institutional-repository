<?php
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Template\THtmlRenderer;

class ListApprovedWorks extends TPage
{
    private $html;
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Digite o Título do Trabalho');
        $this->form->generateAria();

        try {
            $search = new TEntry('title');
            $this->form->addFields([new TLabel('')], [$search]);
            $this->form->addAction(
                'Buscar',
                new TAction([$this, 'onSearch'], ['param' => ['title' => $search->getValue()]]),
                'fa:search blue'
            );
            $search->setSize('100%');

            $vbox = new TVBox;
            $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $vbox->style = 'width: 100%';
            $vbox->add($this->form);
            parent::add($vbox);

            $dataForm = $this->form->getData();
            $academic_works = [];

            if ($dataForm->title != null) {
                $academic_works = $this->onSearch(['title' => $dataForm->title]);
            } else {
                TTransaction::open('works');
                $con = TTransaction::get();
                $result = $con->query('SELECT * FROM academics_works WHERE isApproved = 1');
                $academic_works = $result->fetchAll();
                TTransaction::close();
            }

            foreach ($academic_works as $academic_work) {
                $authors = explode(',', $academic_work['author']);
                $advisors = explode(',', $academic_work['advisor']);

                $work_id = $academic_work['id'];

                $html = new THtmlRenderer('app/resources/work-approved-list.html');

                $replaces = [
                    'title' => $academic_work['title'],
                    'date' => $academic_work['presentation_date'],
                    'keywords' => $academic_work['keywords'],
                    'file' => $academic_work['file'],
                    'file-label' => 'Arquivo PDF',
                    'work_id' => $work_id,
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

                $vbox = new TVBox;
                $vbox->style = 'width: 100%';
                $vbox->add($html);
                parent::add($vbox);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function onSearch($param)
    {
        TTransaction::open('works');
        $con = TTransaction::get();
        $result = $con->query('SELECT * FROM academics_works WHERE isApproved = 1 AND title LIKE "%' . $param['title'] . '%"');
        $academic_works = $result->fetchAll();
        TTransaction::close();
        return $academic_works;
    }
}