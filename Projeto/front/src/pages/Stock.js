import React, { useState, useEffect } from 'react';
import axios from 'axios';

import { stockApi } from '../config';

import { Button } from '../components/Button';
import { Input } from '../components/Input';

function StockPage() {
  const [products, setProducts] = useState([]);
  const [productName, setProductName] = useState('');
  const [productPrice, setProductPrice] = useState('');
  const [productQuantity, setProductQuantity] = useState('');

  useEffect(() => {
    axios.get(stockApi).then(({data}) => {
      setProducts(data);
    });
  }, []);

  const addProduct = () => {
    const newProduct = {
      name: productName,
      price: productPrice,
      quantity: productQuantity
    };
    axios.post(stockApi, newProduct).then(() => {
      setProducts([...products, newProduct]);
      setProductName('');
      setProductPrice('');
      setProductQuantity('');
    });
  };

  const deleteProduct = (index) => {
    const newProducts = products.filter(p => p.id !== index);
    axios.delete(stockApi + `/${index}`).then(() => {
      setProducts(newProducts);
    });
  };

  const editProduct = (index, field, value) => {
    const newProducts = products.map(p => {
      if (p.id === index) {
        return {
          ...p,
          [field]: value,
        };
      }
      return p;
    })
    setProducts(newProducts);
  };

  const editProductSave = (index) => {
    const finded = products.find(p => p.id === index);
    if (finded) {
      axios.put(stockApi + `/${index}`, finded).then(() => {});
    }
  };

  return (
    <div className="container">
      <h1>Estoque</h1>
      <div style={{marginBottom: 10, border: '1px solid', padding: 10, maxWidth: 500}}> 
        <form >
          <div>
            <label>Nome do produto:</label>
            <Input
              type="text"
              value={productName}
              onChange={(e) => setProductName(e.target.value)}
            />
          </div>
          <div>
            <label>Preço do produto:</label>
            <Input
              type="number"
              value={productPrice}
              onChange={(e) => setProductPrice(e.target.value)}
            />
          </div>
          <div>
            <label>Quantidade em estoque:</label>
            <Input
              type="number"
              value={productQuantity}
              onChange={(e) => setProductQuantity(e.target.value)}
            />
          </div>
          <Button type="button" onClick={addProduct}>
            Adicionar
          </Button>
        </form>
      </div>
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Editar</th>
            <th>Deletar</th>
          </tr>
        </thead>
        <tbody>
          {products.map((product, index) => (
            <tr key={index}>
              <td>
                <Input
                  type="text"
                  value={product.name}
                  onChange={(e) => editProduct(product.id, 'name', e.target.value)}
                />
              </td>
              <td>
                <Input
                  type="number"
                  value={product.price}
                  onChange={(e) => editProduct(product.id, 'price', e.target.value)}
                />
              </td>
              <td>
                <Input
                  type="number"
                  value={product.quantity}
                  onChange={(e) => editProduct(product.id, 'quantity', e.target.value)}
                />
              </td>
              <td>
                <Button type="button" onClick={() => editProductSave(product.id)}>
                  Editar
                </Button>
              </td>
              <td>
                <Button type="button" onClick={() => deleteProduct(product.id)}>
                  Delete
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default StockPage;
