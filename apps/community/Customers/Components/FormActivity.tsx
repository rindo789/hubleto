import React, { Component } from 'react';
import HubletoForm, {HubletoFormProps, HubletoFormState} from "../../../../src/core/Components/HubletoForm";
import FormInput from 'adios/FormInput';
import Lookup from 'adios/Inputs/Lookup';

export interface FormActivityProps extends HubletoFormProps {
  idCustomer: number,
}

export interface FormActivityState extends HubletoFormState {}

export default class FormActivity<P, S> extends HubletoForm<FormActivityProps,FormActivityState> {
  static defaultProps: any = {
    ...HubletoForm.defaultProps,
    model: 'HubletoApp/Community/Customers/Models/CustomerActivity',
  };

  props: FormActivityProps;
  state: FormActivityState;

  translationContext: string = 'HubletoApp\\Community\\Customers\\Loader::Components\\FormActivity';

  renderTitle(): JSX.Element {
    if (this.state.creatingRecord) {
      return <h2>{globalThis.main.translate('New activity for customer')}</h2>;
    } else {
      return (
        <>
          <h2>{this.state.record.subject ?? ''}</h2>
          <small>Activity</small>
        </>
      );
    }
  }

  renderContent(): JSX.Element {
    const R = this.state.record;
    const showAdditional: boolean = R.id > 0 ? true : false;

    return (
      <>
        <FormInput title={this.translate("Customer")} required={true}>
          <Lookup {...this.getInputProps('id_customer')}
            model='HubletoApp/Community/Contacts/Models/Customer'
            endpoint={`customers/get-customer`}
            value={R.id_customer}
            onChange={(value: any) => {
              this.updateRecord({ id_customer: value});
            }}
          ></Lookup>
        </FormInput>
        <FormInput title={"Contact Person"}>
          <Lookup {...this.getInputProps("id_person")}
            model='HubletoApp/Community/Customers/Models/Person'
            endpoint={`contacts/get-customer-contacts`}
            customEndpointParams={{id_customer: R.id_customer}}
            value={R.id_person}
            onChange={(value: any) => {
              this.updateRecord({ id_person: value })
              if (R.id_person == 0) {
                R.id_person = null;
                this.setState({record: R})
              }
            }}
          ></Lookup>
        </FormInput>
        {this.inputWrapper('subject')}
        {this.inputWrapper('id_activity_type')}
        {showAdditional ? this.inputWrapper('completed') : null}
        {this.inputWrapper('date_start')}
        {this.inputWrapper('time_start')}
        {this.inputWrapper('date_end')}
        {this.inputWrapper('time_end')}
        {this.inputWrapper('all_day')}
        {this.inputWrapper('id_user', {readonly: true})}
      </>
    );
  }
}
