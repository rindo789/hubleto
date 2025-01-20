import React, { Component } from 'react';
import { deepObjectMerge, getUrlParam } from 'adios/Helper';
import Form, { FormProps, FormState } from 'adios/Form';

export interface FormProductProps extends FormProps {}

export interface FormProductState extends FormState {}

export default class FormProduct<P, S> extends Form<FormProductProps,FormProductState> {
  static defaultProps: any = {
    ...Form.defaultProps,
    model: 'HubletoApp/Community/Products/Models/Product',
  };

  props: FormProductProps;
  state: FormProductState;

  translationContext: string = 'mod.core.products.FormProduct';

  constructor(props: FormProductProps) {
    super(props);
    this.state = {
      ...this.getStateFromProps(props)
    };
  }

  getStateFromProps(props: FormProductProps) {
    return {
      ...super.getStateFromProps(props),
    };
  }

  renderHeaderLeft(): JSX.Element {
    return <>
      {this.state.isInlineEditing ? this.renderSaveButton() : this.renderEditButton()}
    </>;
  }

  renderHeaderRight(): JSX.Element {
    return <>
      {this.state.isInlineEditing ? this.renderDeleteButton() : null}
      {this.props.showInModal ? this.renderCloseButton() : null}
    </>;
  }

  renderTitle(): JSX.Element {
    if (getUrlParam('recordId') == -1) {
      return <h2>{globalThis.main.translate('New Product')}</h2>;
    } else {
      return <h2>{this.state.record.title ? this.state.record.title : '[Undefined Product Name]'}</h2>
    }
  }

  renderContent(): JSX.Element {
    const R = this.state.record;
    const showAdditional = R.id > 0 ? true : false;7

    return (<>
      <div className='card-body flex flex-row gap-2'>
        <div className='grow'>
          {this.inputWrapper('title')}
          {this.inputWrapper('unit_price')}
          {this.inputWrapper('tax')}
          {this.inputWrapper('margin')}
          {this.inputWrapper('unit')}
          {this.inputWrapper('id_supplier')}
        </div>
        <div className='border-l border-gray-200'></div>
        <div className='grow'>
          {this.inputWrapper('image')}
          {this.inputWrapper('description')}
          {this.inputWrapper('id_product_group')}
          {this.inputWrapper('count_in_package')}
          {this.inputWrapper('is_on_sale')}
          {this.inputWrapper('sale_ended')}
        </div>
      </div>
      <div className='card-body border-t border-gray-200 flex flex-row gap-2'>
        <div className='grow'>
          {this.inputWrapper('is_single_order_posible')}
          {this.inputWrapper('show_price')}
          {this.inputWrapper('packaging')}
          {this.inputWrapper('needs_reodering')}
          {this.inputWrapper('supplier')}
        </div>
        <div className='border-l border-gray-200'></div>
        <div className='grow'>
          {this.inputWrapper('price_after_reweight')}
          {this.inputWrapper('storage_rules')}
          {this.inputWrapper('table')}
        </div>
      </div>
    </>);
  }
}