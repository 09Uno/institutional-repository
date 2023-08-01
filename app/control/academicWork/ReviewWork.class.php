<?php
use Adianti\Control\TPage;
use Adianti\Control\TWindow;
use Adianti\Database\TTransaction;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Dialog\TInputDialog;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;


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

                $vbox = new TVBox;

                $vbox->style = 'width: 100%';
                $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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
                    // $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
                    $vbox->add($html);
                    parent::add($vbox);
                }

            }

            TTransaction::close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }



    }


    function testePHP()
    {


    }

    function onFileClick($param)
    {
        TTransaction::open('works');

        if (isset($_GET['work_id'])) {
            $work_id = $_GET['work_id'];
            $work = AcademicWork::find($work_id);

            if ($work) {
                $file = $work->file;
                $file_data = json_decode(urldecode($file), true);

                if (isset($file_data['fileName']) && file_exists($file_data['fileName'])) {
                    $file_name = $file_data['fileName'];

                    $window = TWindow::create('Arquivo PDF', 0.8, 0.8);
                    $object = new TElement('object');
                    $object->data = $file_name;
                    $object->type = 'application/pdf';
                    $object->style = "width: 100%; height: calc(100% - 10px)";
                 
                    $window->add($object);
                    $window->show();




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
    }


    function onApproveClick()
    {
        $work_id = $_GET['work_id'];
        $action1 = new TAction(array($this, 'onAprove'));
        $action2 = new TAction(array($this, 'onAction2'));

        $action1->setParameter('parameter', $work_id);
        $action2->setParameter('parameter', 2);

        $form = new BootstrapFormBuilder('form_approve');
        $comment = new TText('comment');
        $form->addFields([new TLabel('Comentário')], [$comment]);
        $form->addAction('Aprovar', $action1, 'fa:save green');
        $form->addAction('Cancelar', $action2, 'fa:save red');

        new TInputDialog('Aprovar Trabalho', $form);
        TScript::create(" tmenubox_open('Aprovar Trabalho', '{$form}'); ");

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
                $user_id = $work->user_id;

                $comment = $param['comment'];


                SystemNotification::register($user_id, 'Trabalho aprovado', $comment, 'class=CreateAcademicWork', 'Ver Trabalho');

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

        $form = new BootstrapFormBuilder('form_approve');
        $comment = new TText('comment');
        $form->addFields([new TLabel('Comentário')], [$comment]);
        $form->addAction('Desaprovar', $action1, 'fa:save green');
        $form->addAction('Cancelar', $action2, 'fa:save red');

        new TInputDialog('Reprovar Trabalho', $form);
        TScript::create(" tmenubox_open('Desaprovar Trabalho', '{$form}'); ");

    }

    function onDisapprove($param)
    {
        try {
            TTransaction::open('works');

            $work_id = $param['parameter'];
            $work = new AcademicWork($work_id);

            $user_id = $work->user_id;
            $work->delete();
            $comment = $param['comment'];
            SystemNotification::register($user_id, 'Trabalho Reprovado', $comment, 'class=CreateAcademicWork', '');

            TToast::show('show', 'Ação realizada com sucesso', 'top right', 'far:check-circle');



            TTransaction::close();
        } catch (Exception $e) {
            TToast::show('error', 'Erro ao realizar ação', 'top right', 'fas:exclamation-triangle');
        }
    }




}