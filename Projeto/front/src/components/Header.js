import React from "react";
import styled from "styled-components";

import { Button } from "./Button";

import { Link } from "react-router-dom";

const HeaderWrapper = styled.header`
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #f5f5f5;
  box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
`;

const Logo = styled.h1`
  font-size: 2rem;
  color: #333;
`;

const ButtonWrapper = styled.div`
  display: flex;
`;

function Header() {
  return (
    <HeaderWrapper>
      <Logo>ERP</Logo>
      <ButtonWrapper>
        <Link to='/'>
            <Button>Vendas</Button>
        </Link>
        <Link to='/stock'>
            <Button>Estoque</Button>
        </Link>
        <Link to='/report'>
            <Button>Relatórios</Button>
        </Link>
      </ButtonWrapper>
    </HeaderWrapper>
  );
}

export default Header;
