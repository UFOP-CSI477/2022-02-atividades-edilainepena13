import React, {useState, useEffect} from "react";
import axios from 'axios';
import Modal from 'react-modal';

import { Input } from "../components/Input";
import { Button } from "../components/Button";

import { stockApi, sellsApi } from "../config";

function Sells() {
  const [productName, setProductName] = useState('');
  const [paymentType, setPaymentType] = useState('');
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState([]);
  const [modalIsOpen, setIsOpen] = useState(false);

  useEffect(() => {
    axios.get(stockApi).then(({data}) => {
      setProducts(data);
    });
  }, []);

  const searchProduct = () => {
    axios.get(stockApi).then(({data}) => {
      const p = productName.length > 0 ? 
      data.filter(d => d.name.toLowerCase().includes(productName.toLowerCase()))
      : data;
  
      setProducts(p);
    });
  }

  const checkout = () => {
    let group = {};
    cart.map(c => {
      group = {
        ...group,
        [c.id]: group[c.id] ? (group[c.id] + 1) : 1, 
      }
    });
    const approve = Object.keys(group).some((key) => {
      const finded = products.find(p => `${p.id}` === `${key}`);
      if (finded) {
        return Number(finded.quantity) < group[key] ? false : true;
      }
      return false;
    });
    if (approve) {
      openModal();
    } else {
      alert('Há produtos que excedem o estoque no carrinho');
    }
  };

  function openModal() {
    setIsOpen(true);
  }

  function closeModal() {
    setIsOpen(false);
  }

  const handlePaymentTypeChange = (event) => {
    setPaymentType(event.target.value);
  };

  const finishSell = () => {
    if (paymentType === '') return alert('Selecione um pagamento');

    let group = {};
    cart.map(c => {
      group = {
        ...group,
        [c.id]: group[c.id] ? (group[c.id] + 1) : 1, 
      }
    });
    const newQuantity = Object.keys(group).map((key) => {
      const finded = products.find(p => `${p.id}` === `${key}`);
      if (finded) {
        return {
          id: key,
          newQuantity: finded.quantity - group[key],
        }
      }
    }).filter(k => k);

    axios.post(sellsApi, {
      // products: cart,
      value: cart.reduce((acc, cur) => Number(acc) + Number(cur.price), 0),
      quantity: cart.length,
      newQuantity,
      paymentType,
    }).then(() => { 
      setCart([]);
      closeModal();
      alert('Finalizado com sucesso!');
    })
  };

  return (
    <div style={{width: '100%', minHeight: '75vh', display: 'flex', padding: 15}}>
      <Modal
        isOpen={modalIsOpen}
        onRequestClose={closeModal}
        style={{
          content: {
            top: '50%',
            left: '50%',
            right: 'auto',
            bottom: 'auto',
            marginRight: '-50%',
            transform: 'translate(-50%, -50%)',
          },
        }}
        contentLabel="Meios de pagamento"
      >
        <div style={{display: "flex"}}>
          <h2>Meios de pagamento</h2>
          <button style={{
            border: 'none', 
            backgroundColor: 'transparent', 
            marginLeft: 20, 
            cursor: 'pointer',
            fontSize: 16
          }} onClick={closeModal}>X</button>
        </div>
        <form style={{marginBottom: 10}}>
          <div>
            <label>
              <input
                type="radio"
                value="Card"
                checked={paymentType === 'Card'}
                onChange={handlePaymentTypeChange}
              />
              Cartão
            </label>
          </div>
          <div>
            <label>
              <input
                type="radio"
                value="Money"
                checked={paymentType === 'Money'}
                onChange={handlePaymentTypeChange}
              />
              Dinheiro
            </label>
          </div>
        </form>
        <Button onClick={() => finishSell()}>
          Finalizar venda
        </Button>
      </Modal>
      <div style={{width: '50%', padding: 10}}>
        <div>
          <label>Nome do produto:</label>
          <Input
            type="text"
            value={productName}
            onChange={(e) => setProductName(e.target.value)}
          />
          <Button onClick={searchProduct}>
            Buscar
          </Button>
        </div>
        <div style={{marginTop: 10}}>
          {products.length === 0 && <span style={{opacity: 0.6}}>Sem resultados</span>}
          {products.map(product => 
            <div style={{
              padding: 15,
              border: '1px solid',
              display: "flex",
              justifyContent: 'space-between'
            }}
              onClick={() => setCart(old => [...old, product])}
            >
              <div>
                {product.name}
              </div>
              <div>
                Qtd: {product.quantity}
              </div>
              <div>
                R${product.price}
              </div>
            </div>
          )}
        </div>
      </div>
      <div style={{
        width: '50%',
        borderLeft: '1px solid', 
        padding: 15}}
      >
        <Button onClick={() => setCart([])}>
          Nova venda
        </Button>
        <div style={{marginTop: 15, height: 400, overflowY: 'scroll'}}>
          {cart.length === 0 && <span style={{opacity: 0.6}}>Não há itens</span>}
          {cart.map((product, index) => 
            <div style={{
              padding: 15,
              border: '1px solid',
              display: "flex",
              justifyContent: 'space-between'
            }}
              onClick={() => setCart(old => old.filter((_, i) => i !== index))}
            >
              <div>
                {product.name}
              </div>
              <div>
                R${product.price}
              </div>
            </div>
          )}
        </div>
        <div 
          style={{
            marginTop: 15, 
            borderTop: '1px solid', 
            padding: 15, 
            minHeight: 100, 
            maxHeight: 120,
            display: 'flex'
          }}
        >
          <div style={{width: '50%'}}>
            <h3>
              Itens: {cart.length} 
            </h3>
            <h3>
              Total: R${cart.reduce((acc, cur) => Number(acc) + Number(cur.price), 0)}
            </h3>
          </div>
          <div style={{width: '50%', alignItems: 'center', display: 'flex', justifyContent: 'center'}}>
            <Button onClick={() => checkout()}>Pagamentos</Button>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Sells;
