<?php
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TFile;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TMultiEntry;
use Adianti\Widget\Form\TText;
use Adianti\Wrapper\BootstrapFormBuilder;
use Adianti\Base\AdiantiFileSaveTrait;


class EditAcademicWork extends TPage
{

    public function __construct()
    {
        parent::__construct(); // é fundamental executar o construtor da classe pai
        // cria o label
        
        
        $this->form = new BootstrapFormBuilder;
        
        $this->form->setFormTitle('Edite Seu Trabalho');
        $this->form->generateAria();
        
        parent::add($this->form);
    
        $authors = new TEntry('authors');
        
        $id = new TEntry('id');
        $title = new TEntry('title');
        $author = new TEntry('author[]');
        $advisor = new TEntry('advisor[]');
        // $co_advisor = new TEntry('co_advisor');
        $abstract = new TText('abstract');
        $keywords = new TMultiEntry('keywords');
        $presentation_date = new TDate('presentation_date');
        $research_area = new TEntry('research_area');
        $file = new TFile('file');
        
        $this->fieldlist = new TFieldList;
        $this->fieldlist->generateAria();
        $this->fieldlist->width = '100%';
        $this->fieldlist->name = 'Autor';
        $this->fieldlist->addField( '', $author, ['width' => '100%'] );


        $this->fieldlist2 = new TFieldList;
        $this->fieldlist2->generateAria();
        $this->fieldlist2->width = '100%';
        $this->fieldlist2->name = 'Orientador';
        $this->fieldlist2->addField( '', $advisor, ['width' => '100%'] );

        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel('Título do trabalho')], [$title]);
      

        $this->form->addFields([new TLabel('Resumo')], [$abstract]);
        $this->form->addContent([ new Tlabel ('Autores')] , [$this->fieldlist] , 
                                [ new Tlabel ('Orientadores')] , [$this->fieldlist2]
        );
        $this->form->addFields([new TLabel('Palavras-chave')], [$keywords]);
        $this->form->addFields([new TLabel('Data de apresentação')], [$presentation_date]);
        $this->form->addFields([new TLabel('Área de pesquisa')], [$research_area]);

        $this->form->addFields([new TLabel('Arquivo PDF')], [$file]);

        $this->form->addAction('Salvar Alterações', new TAction([$this, 'onSave']), 'fa:save green');
        
        $this->fieldlist->addHeader();
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addCloneAction();

        $this->fieldlist2->addHeader();
        $this->fieldlist2->addDetail( new stdClass );
        $this->fieldlist2->addCloneAction();

        $id-> setEditable(false);
        $id-> setSize('100%');

        $title-> setSize('100%');
        $author-> setSize('100%');
        $advisor-> setSize('100%');

        // $co_advisor-> setSize('50%');
        $abstract-> setSize('100px', 80);
        $keywords-> setSize('100%');
        $keywords-> setMaxSize(5);

        $presentation_date-> setSize('40%');
        $presentation_date->setMask('dd/mm/yyyy');
        $presentation_date->setDatabaseMask('yyyy-mm-dd');
        $presentation_date->setValue( date('d/m/Y') );

        $research_area-> setSize('100%');

    
        $file->setAllowedExtensions( ['pdf'] );
        $file->enableFileHandling();

    }

    public function onEdit($param)
    {
        try {
            TTransaction::open('works');
            $this->workId = $param['work_id']; // Armazena o valor de work_id na variável de instância
            
            $academic_work = new AcademicWork($this->workId);  
            $academic_work->id = $academic_work->id;      
            $academic_work->author = json_decode($academic_work->author);
            $academic_work->advisor = json_decode($academic_work->advisor);
            $academic_work->keywords = json_decode($academic_work->keywords);
            $this->form->setData($academic_work);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
   }

    function onSave($param)
    {
        try {
            TTransaction::open('works');
            $data = $this->form->getData();
            $id = $data->id;
            $authorToReq = $param['author'];
            $advisorToReq = $param['advisor'];
            
            $academic_work = new AcademicWork($id);
            $academic_work->id = $id;
            $academic_work->title = $data->title;
            $academic_work->author = json_encode($authorToReq);
            $academic_work->advisor = json_encode($advisorToReq);
            $academic_work->abstract = $data->abstract;
            $academic_work->keywords = json_encode($data->keywords);
            $academic_work->presentation_date = $data->presentation_date;
            $academic_work->research_area = $data->research_area;
            $academic_work->file = $data->file;
            $academic_work->user_id = TSession::getValue('userid');
    
            $academic_work->store(); // Use 'store' para atualizar o registro existente
            
    
            new TMessage('info', 'Trabalho Editado com sucesso!');
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    
    
}


   


