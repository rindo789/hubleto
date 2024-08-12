import React, { Component } from 'react'
import Table, { TableProps, TableState } from 'adios/Table';
import FormPerson from './FormPerson';

interface TablePersonProps extends TableProps {
  endpoint: string,
  account: number,
}

interface TablePersonState extends TableState {
}

export default class TablePerson extends Table<TablePersonProps, TablePersonState> {
  static defaultProps = {
    itemsPerPage: 15,
    formUseModalSimple: true,
    model: 'CeremonyCrmApp/Modules/Core/Customers/Models/Person',
  }

  props: TablePersonProps;

  getEndpointUrl(): string {
    return this.props.endpoint;
  }

  getFormModalParams(): any {
    return {
      ...super.getFormModalParams(),
      // type: 'centered tiny',
    }
  }

  getFormParams(): any {
    return {
      ...super.getFormParams(),
      isInlineEditing: true,
    }
  }

  loadParams(successCallback?: (params: any) => void): void {
    this.setState({
      addButtonText: 'Add Person',
      title: "Persons",
      showHeader: true,
      canCreate: this.props.canCreate ?? true,
      canDelete: this.props.canDelete ?? true,
      canRead: this.props.canRead ?? true,
      canUpdate: this.props.canUpdate ?? true,
      columns: {
        first_name: { type: 'varchar', title: 'First Name' },
        last_name: { type: 'varchar', title: 'Last Name'},
        id_company: { type: 'lookup', title: 'Company', 'model': 'CeremonyCrmApp/Modules/Core/Customers/Models/Company', },
        virt_address: { type: 'varchar', title: 'Main Address',},
        virt_email: { type: 'varchar', title: 'Main Email',},
        virt_number: { type: 'varchar', title: 'Main Phone Number',},
      },
    });
  }

  renderForm(): JSX.Element {
    let formParams = this.getFormParams();
    formParams.account = this.props.account;
    return <FormPerson {...formParams}/>;
  }
}