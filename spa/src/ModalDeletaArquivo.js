import React, { Component } from 'react'
import 'materialize-css/dist/css/materialize.min.css'
import M from 'materialize-css'

class ModalDeletaArquivo extends Component {

    constructor(props) {
        super(props)
        this.elem = null
        this.instance = null
        this.abrirModal = this.abrirModal.bind(this)
    }

    state = {}

    componentDidMount() {
        this.elem = document.getElementById('mdl-deleta-arquivo')
        this.instance = M.Modal.init(this.elem, {})
        this.props.setAbreModal(this.abrirModal)
    }

    abrirModal = (arquivo) => {
        this.instance.open()
        M.updateTextFields()
        this.setState({
            ...arquivo
        })
    }

    deletaAquivo = e => {
        e.preventDefault()
        let id = this.state.id
        fetch('http://gdocs.test/deletaarquivo/'+id, {
            method: 'delete'
        }).then(data => {
            M.toast({html: 'Arquivo deletado'})
            this.props.refreshArquivos()
            this.instance.close()
        })
    }


    render() {
        return (
            <div id="mdl-deleta-arquivo" className="modal">
                <div className="modal-content">
                    <p>Deletar arquivo {this.state.nomeoriginal} ?</p>
                </div>
                <div className="modal-footer">
                    <a onClick={e => this.deletaAquivo(e)} href="#!" class="modal-close waves-effect waves-green btn-flat">Deletar</a>
                </div>
            </div>
        )
    }


}

export default ModalDeletaArquivo