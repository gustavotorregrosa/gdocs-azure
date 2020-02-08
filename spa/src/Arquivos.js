import React, { Component } from 'react'
import 'materialize-css/dist/css/materialize.min.css'
import M from 'materialize-css'
import TabelaArquivos from './TabelaArquivos'
import DeletaArquivo from './ModalDeletaArquivo'

class Arquivos extends Component {

    state = {
        arquivos: []
    }

    componentDidMount() {
        this.getArquivos()
    }

    
    abreModalDeleta = (e, id) => {
        e.preventDefault()
        let arquivo = this.state.arquivos.filter(el => el.id == id)
        arquivo = arquivo[0]
        this.childAbreModalDeleta(arquivo)

    }     

    chamaDownload = (e, id) => {
        e.preventDefault()
        let nome
        this.state.arquivos.map(arq => {
            if (arq.id == id) {
                nome = arq.nome
            }
        })
        let url = 'http://gdocs.test/arquivos/' + nome
        this.openInNewTab(url)
    }


    chamaDownloadGDocs = (e, id) => {
        e.preventDefault()
        // let nome
        // this.state.arquivos.map(arq => {
        //     if (arq.id == id) {
        //         nome = arq.nome
        //     }
        // })
        let url = 'http://gdocs.test/getarquivos/gdocs/' + id
        this.openInNewTab(url)
    }


    openInNewTab = (url) => {
        // let win = window.open(url, '_blank');
        let win = window.open(url, 'Download');
        win.focus();
    }

    getArquivos = () => {
        fetch('http://gdocs.test/getarquivos', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'get'
        }).then(data => data.json()).then(data => {
            this.setState({
                arquivos: data
            })
        })
    }



    enviaArquivos = (e) => {
        e.preventDefault()
        let arquivosRaw = document.getElementById("meu-input").files
        this.converteArrayArquivos(arquivosRaw).then(r => {
            fetch('http://gdocs.test/salvaarquivos', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'post',
                body: JSON.stringify({
                    arquivos: r
                })
            }).then(data => this.getArquivos())
        })
    }

    converteUnit = f => new Promise(sucesso => {
        const reader = new FileReader()
        reader.readAsDataURL(f)
        reader.onload = () => sucesso(reader.result)
    })

    converteArrayArquivos = arquivos => new Promise(sucesso => {
        let arquivosArray = Array.from(arquivos)
        let arquivosConvertidos = []
        arquivosArray.map((a) => {
            this.converteUnit(a).then(r => {
                r = r.split('base64,')[1]
                let arqTemp = {
                    name: a.name,
                    type: a.type,
                    base64: r
                }
                arquivosConvertidos.push(arqTemp)
            })
        })
        setTimeout(() => {
            sucesso(arquivosConvertidos)
        }, 1000)
    })

    render() {
        return (
            <div className="container">
                <div className="row">
                    <div className="col s6">
                        <form action="#">
                            <div className="file-field input-field">
                                <div className="btn">
                                    <span>File</span>
                                    <input id="meu-input" type="file" multiple />
                                </div>
                                <div className="file-path-wrapper">
                                    <input className="file-path validate" type="text" placeholder="Upload one or more files" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div className="col s1">
                        <a onClick={e => this.enviaArquivos(e)} className="waves-effect waves-teal btn-flat">Enviar</a>
                    </div>
                </div>
                <br /><br /><br /><br /><br /><br />
                <div className="row">
                    <TabelaArquivos ativaDownloadGDocs={(e, key) => this.chamaDownloadGDocs(e, key)} abreModalDeleta={(e, key) => this.abreModalDeleta(e, key)} ativaDownload={(e, key) => this.chamaDownload(e, key)} arquivos={this.state.arquivos} />

                </div>
                <DeletaArquivo refreshArquivos={() => this.getArquivos()} setAbreModal={f => this.childAbreModalDeleta = f}/>
            </div>

        )
    }
}

export default Arquivos
