@ECHO OFF
TITLE DeepSeek - Configurar Memória 16GB
ECHO ========================================
ECHO  Configurando ambiente para DeepSeek
ECHO ========================================
ECHO.

:: Aumentar pagefile do Windows para 16GB
ECHO [1/3] Aumentando pagefile do Windows para 16GB...
wmic computersystem where name="%COMPUTERNAME%" set AutomaticManagedPagefile=False
wmic pagefileset where name="C:\\pagefile.sys" set InitialSize=16384,MaximumSize=16384
ECHO OK - Pagefile configurado para 16GB
ECHO.

:: Configurar Node.js para usar até 16GB de heap
ECHO [2/3] Verificando script deepseek-ram.cmd...
IF EXIST deepseek-ram.cmd (
    ECHO OK - deepseek-ram.cmd ja existe
) ELSE (
    ECHO Criando deepseek-ram.cmd...
    (
        ECHO @ECHO off
        ECHO SET NODE_OPTIONS=--max-old-space-size=16384
        ECHO ECHO Memory limit: 16GB
        ECHO ECHO Starting DeepSeek Code...
        ECHO ECHO.
        ECHO deepseek
    ) > deepseek-ram.cmd
    ECHO OK - deepseek-ram.cmd criado
)
ECHO.

:: Recomendar restart
ECHO [3/3] Configuracao concluida!
ECHO.
ECHO ========================================
ECHO  Para aplicar as alteracoes:
ECHO  1. Execute este script COMO ADMINISTRADOR
ECHO     (clique com botao direito ^> Executar como administrador)
ECHO  2. Reinicie o computador
ECHO  3. Execute deepseek-ram.cmd para iniciar o DeepSeek
ECHO ========================================

PAUSE
