package com.exemplo.app;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {

    private ImageButton notificacoesBtn, filaEsperaBtn, logoutBtn;
    private TextView nomeTxt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        notificacoesBtn = (ImageButton) findViewById(R.id.notificacoesBtn);
        filaEsperaBtn = (ImageButton) findViewById(R.id.filaEsperaBtn);
        logoutBtn = (ImageButton) findViewById(R.id.logoutBtn);
        nomeTxt = (TextView) findViewById(R.id.nomeTxt);

        nomeTxt.setText(GlobalVar.nome);


        //responsavel por implementar todos os eventos de botoes
        cadastroEventos();
    }

    private void cadastroEventos(){
        notificacoesBtn.setOnClickListener(new View.OnClickListener() { //muda de tela
            @Override
            public void onClick(View view) {
                Intent trocaAct = new Intent(MainActivity.this, visualizarNotificacoes.class);

                //pedimos para iniciar a activity passada como parâmetro
                startActivity(trocaAct);
            }
        });

        filaEsperaBtn.setOnClickListener(new View.OnClickListener() { //muda de tela
            @Override
            public void onClick(View view) {
                Intent trocaAct = new Intent(MainActivity.this, visualizarFilaEspera.class);

                //pedimos para iniciar a activity passada como parâmetro
                startActivity(trocaAct);
            }
        });

        //falta implementar o botão logout

        logoutBtn.setOnClickListener(new View.OnClickListener() { //muda de tela
            @Override
            public void onClick(View view) {
                GlobalVar.cpf = null;
                GlobalVar.nome = null;
                Intent trocaAct = new Intent(MainActivity.this, Login.class);

                //exclui activities anteriores da pilha de historico
                trocaAct.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);

                //pedimos para iniciar a activity passada como parâmetro
                startActivity(trocaAct);
            }
        });
    }
}