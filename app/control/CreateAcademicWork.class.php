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



class CreateAcademicWork extends TPage
{
    private $form; // rótulo

    public function __construct()
    {
        parent::__construct(); // é fundamental executar o construtor da classe pai
        // cria o label
        
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastrar Trabalho Acadêmico');
        $this->form->generateAria();
        
        parent::add($this->form);
    

        
        $title = new TEntry('title');
        $author = new TEntry('author');
        $advisor = new TEntry('advisor');
        // $co_advisor = new TEntry('co_advisor');
        $abstract = new TText('abstract');
        $keywords = new TMultiEntry('keywords');
        $presentation_date = new TDate('presentation_date');
        $research_area = new TEntry('research_area');
        $file = new TFile('file');
        

        $this->form->addFields([new TLabel('Título do trabalho')], [$title]);
        $this->form->addFields([new TLabel('Autor')], [$author],
                                [new TLabel('Orientador')], [$advisor]);
        // $this->form->addFields([new TLabel('Co-orientador')], [$co_advisor]);
        $this->form->addFields([new TLabel('Resumo')], [$abstract]);
        $this->form->addFields([new TLabel('Palavras-chave')], [$keywords]);
        $this->form->addFields([new TLabel('Data de apresentação')], [$presentation_date]);
        $this->form->addFields([new TLabel('Área de pesquisa')], [$research_area]);

        $this->form->addFields([new TLabel('Arquivo PDF')], [$file]);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save green');
        


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

    public function onSave($param){
        try{

            $data = $this->form->getData();

            TTransaction::open('works');
            $academic_work = new AcademicWork;    
            $academic_work->title = $data->title;
            $academic_work->author = $data->author;
            $academic_work->advisor = $data->advisor;
            // $academic_work->co_advisor = $this->form->getData('co_advisor');
            $academic_work->abstract = $data->abstract;
            $academic_work->keywords = json_encode($data->keywords);
            $academic_work->presentation_date = $data->presentation_date;
            $academic_work->research_area = $data->research_area;
            $academic_work->file = $data->file;

            $academic_work->store();

            new TMessage('info', 'Trabalho cadastrado com sucesso!');

            

            TTransaction::close();

        }catch(Exception $e){
            new TMessage('error', $e->getMessage());
        }
    }

   


}